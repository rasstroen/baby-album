<?php

class Config {

    const MEDIA_TYPE_AVATAR = 3;
    const MEDIA_TYPE_PHOTO = 4;
    //
    const USER_ROLE_UNVERIFIED = 0;
    const USER_ROLE_VERIFIED = 10;
    const USER_ROLE_ADMIN = 50;
    //
    const ALBUM_ITEM_TYPE_PIC = 1;
    const ALBUM_ITEM_TYPE_PARAMS = 2;
    const ALBUM_ITEM_TYPE_TEXT = 3;

    public static $eyecolors = array(
        1 => 'серые',
        2 => 'синие',
        3 => 'зелёные',
        4 => 'карие',
    );

    private static $config = array(
        'www_folder' => '',
        'static_path' => '/home/balbum.ru/static',
        'www_domain' => 'balbum.ru',
        'www_path' => '/',
        'templates_root' => 'templates',
        'dbname' => 'baby_album',
        'dbuser' => 'root',
        'dbhost' => 'localhost',
        'dbpass' => '2912',
    );

    public static function need($field, $default = false) {
        return isset(self::$config[$field]) ? self::$config[$field] : $default;
    }

    public static function set($field, $value) {
        self::$config[$field] = $value;
    }

}