<?php

/**
 * При загрузке файла на сервер происходит:
 * 1. Грузим файл на сервер, скармливаем его классу ImgStore методом upload()
 * 2. Создается n записей в бд, где n- колво запрошенных размеров + 1 на оригинал
 * 3. Демон ресайзит такие файлы в фоновом режиме и проставляет в базе статус ready при удачном ресайзе
 * 4. Ajax на стороне клиента опрашивает сервер на предмет готовности картинки. Метод getProgress отдает % готовности
 * (загрузки картинки на сервер)
 * 5. Пока картинка не отресайжена, метод getUrl() отдает картинку нужного размера с сообщением "картинка готовицца
 * 6. server_id хранит сервер с картинкой. 0 - наш сервер, 1 - амазон и т.п., т.е. служит как префикс
 */
class ImgStore {
    // errors

    const ERROR_NOT_READABLE = 1;
    // crop
    const CROP_METHOD_CROP_TO_SIZE = 0; // вписываем в размеры и отрезаем лишнее
    const CROP_METHOD_KEEP_PROPORTIONS = 1; // вписываем в размеры ресайзом
    // server list
    const SERVER_ORIG = 1;
    const SERVER_AMAZONS3 = 2;
    const SERVER_PRIVATE = 3;
    //
    const ROOT_FOLDER = '/home/images/';
    const ROOT_PRIVATE_FOLDER = '/home/images_private/';

    public static $server_urls = array(
        self::SERVER_ORIG => 'http://st.balbum.ru/',
        self::SERVER_AMAZONS3 => 'https://s3-eu-west-1.amazonaws.com/balbum/',
        self::SERVER_PRIVATE => 'http://pc.balbum.ru/',
    );

    public static function resize($orig_file_path, $settings, $target_file_path) {
        $quality = 95;

        $crop = $settings['crop_method'];
        $current_width = $settings['width'];
        $current_height = $settings['height'];
        $target_width = min($current_width, $settings['width_requested']);
        $target_height = min($current_height, $settings['height_requested']);

        if ($crop) {
            $x_ratio = $target_width / $current_width;
            $y_ratio = $target_height / $current_height;

            $ratio = min($x_ratio, $y_ratio);
            $use_x_ratio = ($x_ratio == $ratio);
            $new_width = $use_x_ratio ? $target_width : floor($current_width * $ratio);
            $new_height = !$use_x_ratio ? $target_height : floor($current_height * $ratio);
        } else {
            $new_width = $target_width;
            $new_height = $target_height;
        }

        $im = new imagick($orig_file_path);

        $im->cropThumbnailImage($new_width, $new_height);
        $im->setImageCompression(imagick::COMPRESSION_JPEG);
        $im->setImageCompressionQuality($quality);
        $im->stripImage();
        $result = $im->writeImage($target_file_path);
        $im->destroy();
        return array($new_width, $new_height, $target_width, $target_height);
    }

    /**
     * структура таблицы
     * 
     * id - autoincrement
     * image_id - уникальный идентификатор картинки
     * size_id - id размера картинки. 0-оригинал
     * crop_method - как кропать
     * is_orig
     * width_requested - запрошенная ширина изображения
     * height_requested - запрошенная высота изображения
     * width - полученная ширина изображения
     * height - полученная высота изображения
     * server_id - id сервера
     * add_time - время создания записи
     * photo_time - время фото
     * photo_dpi - dpi
     * uploaded - флаг, файл загружен
     * ready - флаг, файл обработан
     * private - флаг, файл приватный (отдается по другим url)
     * bytes - размер файла
     * deleted - удаленное изображение. пропадет из таблицы при реальном физическом удалении
     * error_code - код ошибки при обработке/ресайзе
     */
    public static function getImageProperties($tmp_name, $full = false) {
        $props_ = getimagesize($tmp_name);
        $ex = @exif_read_data($tmp_name);
        if ($ex)
            $props_+=$ex;
        if ($full)
            return $props_;
        // mime
        $props['mime'] = $props_['mime'];
        // width
        $props['width'] = $props_[0];
        // file size
        $props['size'] = isset($props_['FileSize']) ? $props_['FileSize'] : filesize($tmp_name);
        // height
        $props['height'] = $props_[1];
        // camera vendor
        $props['vendor'] = isset($props_['Make']) ? $props_['Make'] : '';
        // camera model
        $props['model'] = isset($props_['Model']) ? $props_['Model'] : '';
        // orientation
        $props['orientation'] = isset($props_['Orientation']) ? $props_['Orientation'] : 0;
        // time of photo
        $props['photo_time'] = isset($props_['DateTimeOriginal']) ? $props_['DateTimeOriginal'] : 0;
        // programm
        $props['software'] = isset($props_['Software']) ? $props_['Software'] : '';
        // dpi for 10x15cm
        $props['dpi'] = round(max($props['width'], $props['height']) / (15.2 / 2.54));

        return $props;
    }

    public static function getFileLocalPath($image_id, $size_id, $private = false) {
        $md5 = md5($image_id . $size_id . ($private ? 'private' : ''));
        $path = (!$private ? self::ROOT_FOLDER : self::ROOT_PRIVATE_FOLDER) . substr($md5, 0, 2) . '/' . substr($md5, 3, 3) . '/';
        @mkdir($path, 0777, true);
        $file_name = $path . $image_id . '.jpg';
        return $file_name;
    }

    public static function resample($width_orig, $height_orig, $source, $dest, $orientation) {
        // Resample
        if (!in_array($orientation, array(3, 6, 8)))
            return true;
        $image_p = imagecreatetruecolor($width_orig, $height_orig);
        $image = imagecreatefromjpeg($source);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width_orig, $height_orig, $width_orig, $height_orig);
        // Fix Orientation
        switch ($orientation) {
            case 3:
                $image_p = imagerotate($image_p, 180, 0);
                break;
            case 6:
                $image_p = imagerotate($image_p, -90, 0);
                break;
            case 8:
                $image_p = imagerotate($image_p, 90, 0);
                break;
        }
        // Output
        imagejpeg($image_p, $dest, 100);
        return true;
    }

    /**
     * Скармливаем загруженный оригинал картинки, сохраняем на диске, добавляем в очередь на
     * обработку, отдаем уникальный id картинки. В дальнейшем url до картинки можно будет получить
     * ф-цией ImgStore::getUrl() по типу картинки и ее id
     * 
     * @param type $filepath
     * @param type $filetype
     * @param array $sizes array(1=>200x200x1,2=>250x250x2)
     * @return type
     */
    public static function upload($tmp_name, array $sizes, $private = 0) {
        // получаем информацию об изображении
        $time = time();
        $props = self::getImageProperties($tmp_name);
        // поворачиваем, если это нужно
        self::resample($props['width'], $props['height'], $tmp_name, $tmp_name, $props['orientation']);

        // добавляем запись для оригинального изображения
        Database::query('INSERT INTO `images` SET
            `size_id`=0,
            `crop_method`=' . self::CROP_METHOD_KEEP_PROPORTIONS . ',
            `width_requested`=0,
            `is_orig`=1,
            `height_requested`=0,
            `width`=' . $props['width'] . ',
            `height`=' . $props['height'] . ',
            `server_id`=' . self::SERVER_ORIG . ',
            `add_time`=' . $time . ',
            `uploaded`=1,
            `vendor` =' . Database::escape($props['vendor']) . ',
            `model` =' . Database::escape($props['model']) . ',
            `orientation`=' . $props['orientation'] . ',
            `photo_time` =' . ($props['photo_time'] ? strtotime($props['photo_time']) : 0) . ',
            `software` =' . Database::escape($props['software']) . ',
            `dpi`=' . $props['dpi'] . ',
            `ready`=1,
            `private`=' . $private . ',
            `bytes`=' . $props['size'] . ',
            `deleted`=0');
        // получаем id изображения
        $image_id = Database::lastInsertId();
        // перемещаем файл туда, где он должен лежать
        $file_path = self::getFileLocalPath($image_id, 0);
        if (!move_uploaded_file($tmp_name, $file_path)) {
            Database::query('DELETE FROM `images` WHERE `id`=' . $image_id);
            throw new Exception('Cant move uploaded file ' . $tmp_name . ' to ' . $file_path);
        }
        // выставляем `image_id`. этот же image_id будет у всех ресайженных копиях этого изображения
        Database::query('UPDATE `images` SET `image_id`=`id` WHERE `id`=' . $image_id);
        $sql = array();
        foreach ($sizes as $size_id => $size_string) {
            list($width_requested, $height_requested, $crop_method) = explode('x', $size_string);
            $sql[] = '(
                ' . $image_id . ',
                ' . $size_id . ',
                ' . $crop_method . ',
                0,
                ' . $width_requested . ',
                ' . $height_requested . ',
                ' . $time . ',
                0,
                ' . $private . ')';
        }
        $query = 'INSERT INTO `images`(`image_id`,`size_id`,`crop_method`,`is_orig`,`width_requested`,`height_requested`,`add_time`,`ready`,`private`) VALUES ' . implode(',', $sql);
        Database::query($query);
        return $image_id;
    }

    /**
     * Во время загрузки файла считаем его размер. По реальному размеру файла в /tmp
     * вычисляем прогресс загрузки
     * 
     * @param type $image_id
     * @return type
     */
    public static function getUploadProgress($image_id) {
        return $progress;
    }

    /**
     * Получаем url картинки по ее id и размеру
     *
     * @param type $image_id
     * @param type $size
     * @return string url картинки
     */
    public static function getUrl($image_id, $size, $cachebreaker = 'c') {
        $cachebreaker = $cachebreaker ? '?' . $cachebreaker : '';
        $data = Database::sql2row('SELECT `error_code`,`server_id`,`deleted`,`ready`,`private_real` FROM `images` WHERE `image_id`=' . $image_id . ' AND `size_id`=' . $size);
        if (!$data)
            return self::$server_urls[self::SERVER_ORIG] . '404/' . $size . '.png?nofound';
        if ($data['deleted'])
            return self::$server_urls[self::SERVER_ORIG] . '404/' . $size . '.png?deleted';
        if (!$data['ready'])
            return self::$server_urls[self::SERVER_ORIG] . '415/' . $size . '.png?unready' . $cachebreaker;
        if ($data['error_code'])
            return self::$server_urls[self::SERVER_ORIG] . '502/' . $size . '.png?error' . $data['error_code'];
        $md5 = md5($image_id . $size . ($data['private_real'] ? 'private' : ''));

        if ($data['private_real']) {
            $url = self::$server_urls[$data['server_id']] . substr($md5, 0, 2) . '/' . substr($md5, 3, 3) . '/' . $image_id . '-' . $size . '.jpg' . $cachebreaker;
        } else {
            $url = self::$server_urls[$data['server_id']] . substr($md5, 0, 2) . '/' . substr($md5, 3, 3) . '/' . $image_id . '.jpg' . $cachebreaker;
        }


        return $url;
    }

    /**
     * Получаем url картинок определенного размеру по списку id
     * 
     * @param array $image_ids
     * @param type $size
     * @return type
     */
    public static function getUrls(array $image_ids, $size) {
        return $url;
    }

}