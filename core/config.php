<?php

class Config {
    // social

    const APP_ID_VK = '3273664';
    const APP_SECRET_VK = 'Cm9qOMSZYvLKglfM021D';
    //
    const img_prefix = 'http://img.balbum.ru/';
    //
    const COMMENT_OBJECT_ALBUM_EVENT = 1;
    //
    const GLOBAL_EMAIL = 'admin@balbum.ru';
    const GLOBAL_LOGIN = 'admin@balbum.ru';
    const GLOBAL_EMAIL_PWD = 'Lazer2000';
    const GLOBAL_EMAIL_SERVER = 'smtp.gmail.com';
    //
    const T_SIZE_AVATAR = 1;
    const T_SIZE_PICTURE = 2;
    const T_SIZE_ALBUM_COVER = 3;
    const SIZES_AVATAR_SMALL = 10;
    const SIZES_AVATAR_NORMAL = 20;
    const SIZES_PICTURE_SMALL = 30;
    const SIZES_PICTURE_NORMAL = 40;
    const SIZES_PICTURE_BIG = 50;
    const SIZES_ALBUM_COVER_SMALL = 60;
    const SIZES_ALBUM_COVER_NORMAL = 70;
    const SIZES_ALBUM_COVER_BIG = 80;

    public static $sizes = array(
        self::T_SIZE_AVATAR => array(
            self::SIZES_AVATAR_SMALL => '50x50x0',
            self::SIZES_AVATAR_NORMAL => '100x100x0',
        ),
        self::T_SIZE_ALBUM_COVER => array(
            self::SIZES_ALBUM_COVER_SMALL => '150x150x0',
            self::SIZES_ALBUM_COVER_NORMAL => '230x230x0',
            self::SIZES_ALBUM_COVER_BIG => '450x450x1',
        ),
        self::T_SIZE_PICTURE => array(
            self::SIZES_PICTURE_SMALL => '200x200x0',
            self::SIZES_PICTURE_NORMAL => '450x450x1',
            self::SIZES_PICTURE_BIG => '980x980x1',
        ),
    );

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