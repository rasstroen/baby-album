<?php

class Config {
    const img_prefix = 'http://img.balbum.ru/';
    //
    const COMMENT_OBJECT_ALBUM_EVENT = 1;
//
    const GLOBAL_EMAIL = 'admin@balbum.ru';
    const GLOBAL_LOGIN = 'admin@balbum.ru';
    const GLOBAL_EMAIL_PWD = 'Lazer2000';
    const GLOBAL_EMAIL_SERVER = 'smtp.gmail.com';
    //
    const MEDIA_TYPE_AVATAR = 3;
    const MEDIA_TYPE_PHOTO = 4;
    const MEDIA_TYPE_ALBUM_COVER = 5;
    //
    const USER_ROLE_UNVERIFIED = 0;
    const USER_ROLE_VERIFIED = 10;
    const USER_ROLE_ADMIN = 50;
    //
    const ALBUM_ITEM_TYPE_PIC = 1;
    const ALBUM_ITEM_TYPE_PARAMS = 2;
    const ALBUM_ITEM_TYPE_TEXT = 3;

    public static $family = array(
        1 => 'мама',
        2 => 'папа',
        3 => 'бабушка',
        4 => 'дедушка',
        5 => 'дядя',
        6 => 'тётя',
        7 => 'брат',
        8 => 'сестра',
    );
    public static $family_kem = array(
        1 => 'мамой',
        2 => 'папой',
        3 => 'бабушкой',
        4 => 'дедушкой',
        5 => 'дядей',
        6 => 'тётей',
        7 => 'братом',
        8 => 'сестрой',
    );
    //
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