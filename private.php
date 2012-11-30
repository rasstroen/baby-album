<?php

ini_set('display_errors', 1);
$end = array_pop(explode('/', $_SERVER['REQUEST_URI']));
list($image_id, $size) = explode('-', $end);
$image_id = (int) $image_id;
$size = (int) $size;

if ($image_id && $size) {
    $core_path = 'core/';
    require_once $core_path . 'config.php';
    require_once $core_path . 'include.php';
    $authorized = CurrentUser::authorize_cookie();
    if ($authorized) {
        $owner = Database::sql2single('SELECT `creator_id` FROM `album_events` WHERE `picture`=' . $image_id);
        if ($owner == CurrentUser::$id) {
            header('Content-type: image/jpeg');
            header('Content-Disposition: inline; filename=protected_' . $image_id . '-' . $size . '.jpg');
            header('X-Accel-Redirect: /images_private/' . str_replace(ImgStore::ROOT_PRIVATE_FOLDER, '', ImgStore::getFileLocalPath($image_id, $size, $private = true)));
            exit(0);
        } else {
            die('Изображение является приватным и доступно только владельцу');
        }
    } else {
        die('Изображение является приватным и доступно только владельцу');
    }
}