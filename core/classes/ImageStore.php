<?php

class ImageStore {

    /**
     *
     * @param type $path_to_exists_image /tmp/asdaSAd
     * @param type $sizes array(big=>100x100x1,small=>100x200,1,orig=>0,0,0)
     * @return array(small=>15,big=>16)
     */
    public static function store($path_to_exists_image, $sizes, $media_type_id = 1, $remove_old = array()) {
        $sizes_ = array();

        foreach ($sizes as $name => $dimesion_string) {
            $sizes_[] = 'sizes[' . $name . ']=' . $dimesion_string;
        }
        foreach ($remove_old as $id) {
            $remove_old[$id] = 'remove_old[' . $id . ']=' . $id;
        }
        $url = 'http://img.pis.ec/rpc.php?method=upload&project_id=' . $media_type_id . '&' . implode('&', $sizes_) . '&remove_old=' . implode('&', $remove_old);

        $result = self::curl($url, $path_to_exists_image, true);
        return $result;
    }

    public static function curl($url, $path_to_exists_image, $post = false) {
        if (!$post) {
            $q = array();
            foreach ($params as $f => $v)
                $q[] = "$f=" . urlencode($v);
            $url.='?' . implode('&', $q);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post)
            curl_setopt($ch, CURLOPT_POSTFIELDS, array('file' => '@' . $path_to_exists_image));
        $content = curl_exec($ch);
        $data = json_decode(($content), 1);
        return $data;
    }

}