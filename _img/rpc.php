<?php
// http://img.pis.ec/static/PRJ_ID/SIZEKEY/SUBSTR(MD5(AUTOINC,1,4))/AUTOINC.jpg
// WHERE SIZEKEY = $_GET['sizes'][SIZEKEY] , for example sizes[big_]=100x100x1
//$path = 'http://img.pis.ec/static/' . $project_id . '/big_/' . substr(md5($image_id), 1, 4) . '/' . $image_id . '.jpg';
// SO WE NEEED TO SAVE IMAGE ID FOR EACH UPLOADED FILE AND FOR EACH SIZE OF RESULTING FILES!
// PROJECT IDS - ONE ID FOR ONE TYPE OF MULTIMEDIA
// FOR EXAMPLE BALBUM.RU images = 2(with 3 sizes), BALBUM.RU avatars = 3(with 2 sizes)
/*
 * BALBUM.RU
 * 1-100
 *
 * PUBLIC.PIS.EC
 * 101-200
 *
 * MEOW
 * 201-300
 *
 * OTHERS
 * 300-1000 (PLEASE ADD HERE ANY NEW PROJECTS)
 *
 */


ini_set('display_errors', 0);
$request_method = isset($_GET['method']) ? $_GET['method'] : false;
switch ($request_method) {
    case 'upload':
        $result = Img::Upload();
        break;
    case 'delete':
        $result = Img::Delete();
        break;
    case 'class':
        require_once 'test.php';
        exit(0);
        break;
    case 'test':
        ?>
        <form method="post" enctype="multipart/form-data" action="?method=upload&project_id=1&sizes[1]=100x100x1&sizes[2]=200x200x1&sizes[3]=100x200x0">
            <input type="hidden" value="upload" name="method" />
            <input type="file" name="pic1" />
            <input type="file" name="pic2" />
            <input type="file" name="pic3" />
            <input type="file" name="pic4" />
            <input type="submit">
        </form>
        <?php
        exit(0);
        break;
}

echo json_encode($result);
exit(0);

class Img {

    private static $project_id = 0;
    private static $static_root = '/home/img/static';
    private static $static_www_root = 'http://img.pis.ec/static';
    private static $result_images = array();

    private static function Init() {
        self::$project_id = isset($_GET['project_id']) ? $_GET['project_id'] : false;
    }

    public static function Upload() {
        self::Init();
        $out['path'] = 'http://img.pis.ec/static/PRJ_ID/SIZEKEY/SUBSTR(MD5(AUTOINC,1,4))/AUTOINC.jpg';



        if (isset($_FILES)) {
            foreach ($_FILES as $key => $file) {
                if (isset($_GET['remove_old'])) {
                    $sizes = isset($_GET['sizes']) ? $_GET['sizes'] : array('orig' => '0x0x1');
                    $i = 0;
                    foreach ($_GET['remove_old'] as $id) {
                        if (!$id)
                            continue;
                        $sizekey = array_keys($sizes);
                        $sizekey = $sizekey[$i];
                        $ext = 'jpg';
                        $src = self::getImagePath($key, $sizekey, $ext, $id);
                        if (file_exists($src[0])) {
                            @unlink($src[0]);
                            $out['unlinked'][$id] = $id;
                            mysql_connect('localhost', 'root', '2912');
                            mysql_select_db('img');
                            mysql_query('DELETE FROM `img` WHERE id=' . (int) $id);
                        }
                        $i++;
                    }
                }
                if (!$file['error']) {
                    $out['result'][$key] = self::processImage($key);
                } else {
                    $out['result'][$key] = $file['error'];
                }
            }
        }
        $out['result_images'] = self::$result_images;
        return $out;
    }

    public static function processImage($key) {
        $out = array('key' => $key);
        $quality = 94;
        $sizes = isset($_GET['sizes']) ? $_GET['sizes'] : array('orig' => '0x0x1');

        foreach ($sizes as $sizekey => $pair) {
            list($width, $height, $keep_proportions) = explode('x', $pair);
            $sourceInfo = @getimagesize($_FILES[$key]['tmp_name']);
            $out[$sizekey]['dimensions']['width'] = $sourceInfo[0];
            $out[$sizekey]['dimensions']['height'] = $sourceInfo[1];
            $out[$sizekey]['dimensions']['mime'] = $sourceInfo['mime'];

            $width = min($width, $out[$sizekey]['dimensions']['width']);
            $height = min($height, $out[$sizekey]['dimensions']['height']);
            if (!$width || !$height) {
                $width = $sourceInfo[0];
                $height = $sourceInfo[1];
            }

            $out[$sizekey]['dimensions']['target_width'] = $width;
            $out[$sizekey]['dimensions']['target_height'] = $height;
            $out[$sizekey]['dimensions']['keep_proportions'] = $keep_proportions;
            // image
            // if larger than original
            // resizing
            if ($keep_proportions) {
                $x_ratio = $out[$sizekey]['dimensions']['target_width'] / $out[$sizekey]['dimensions']['width'];
                $y_ratio = $out[$sizekey]['dimensions']['target_height'] / $out[$sizekey]['dimensions']['height'];

                $ratio = min($x_ratio, $y_ratio);
                $use_x_ratio = ($x_ratio == $ratio);
                $out[$sizekey]['dimensions']['ratio'] = $ratio;
                $out[$sizekey]['dimensions']['x_ratio'] = $x_ratio;

                $new_width = $use_x_ratio ? $width : floor($out[$sizekey]['dimensions']['width'] * $ratio);
                $new_height = !$use_x_ratio ? $height : floor($out[$sizekey]['dimensions']['height'] * $ratio);
            } else {
                $new_width = $out[$sizekey]['dimensions']['target_width'];
                $new_height = $out[$sizekey]['dimensions']['target_height'];
            }

            $new_width = $new_width ? $new_width : $out[$sizekey]['dimensions']['width'];
            $new_height = $new_height ? $new_height : $out[$sizekey]['dimensions']['height'];

            $out[$sizekey]['dimensions']['new_width'] = $new_width;
            $out[$sizekey]['dimensions']['new_height'] = $new_height;

            foreach (self::$result_images as $image) {
                if ($image[0] == $new_width)
                    if ($image[1] == $new_height) {
                        // такая картинка уже отресайзжена
                        $out[$sizekey]['ID'] = $image[2];
                        $out[$sizekey]['path'] = $image[3];
                        $out[$sizekey]['double'] = true;
                        self::$result_images[$sizekey] = array($new_width, $new_height, $image[2], $image[3]);
                        $real_path = str_replace(self::$static_www_root, self::$static_root, $image[3]);
                        $link_path = self::getImagePath($key, $sizekey, 'jpg', $image[2]);
                        $link_path = $link_path[0];
                        exec('ln -s ' . $real_path . ' ' . $link_path, $output);
                        continue(2);
                    }
            }


            $im = new imagick($_FILES[$key]['tmp_name']);
            list($targetFilePath, $file_id) = self::getImagePath($key, $sizekey, 'jpg');
            $out[$sizekey]['path'] = str_replace(self::$static_root, self::$static_www_root, $targetFilePath);
            $out[$sizekey]['ID'] = $file_id;
            self::$result_images[$sizekey] = array($new_width, $new_height, $file_id, $out[$sizekey]['path']);
            $im->cropThumbnailImage($new_width, $new_height);
            $im->setImageCompression(imagick::COMPRESSION_JPEG);
            $im->setImageCompressionQuality($quality);
            $im->stripImage();
            $result = $im->writeImage($targetFilePath);
            $im->destroy();
        }
        return $out;
    }

    public static function getInc($path, $ext) {
        mysql_connect('localhost', 'root', '2912');
        mysql_select_db('img');
        mysql_query('INSERT INTO `img` SET `project_id`=' . self::$project_id . ',ext=\'' . $ext . '\',path=\'' . $path . '\'');
        return mysql_insert_id();
    }

    public static function getImagePath($key, $sizekey, $ext, $autoincrement = 0) {
        $path = self::$static_root . '/' . self::$project_id . '/' . $sizekey;
        $autoincrement = $autoincrement ? $autoincrement : self::getInc($sizekey, $ext);
        $fname = $path . '/' . substr(md5($autoincrement), 1, 4) . '/';
        @mkdir($fname, 0777, true);
        $fname .= $autoincrement . '.' . $ext;
        return array($fname, $autoincrement);
    }

    public static function Delete() {

    }

}