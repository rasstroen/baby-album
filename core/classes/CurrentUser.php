<?php

class CurrentUser {

    public static $id = false;
    public static $authorized = false;

    public static function set_cookie($user_id) {
        $cookie_key = Config::need('COOKIE_KEY', 'u');
        $hash_coookie_key = $cookie_key . '_sh';
        $uid_coookie_key = $cookie_key . '_id';
        $hash = md5(time() . $user_id);
        Database::query('UPDATE `user` SET `lastAccessTime`=' . time() . ',`session`=' . Database::escape($hash) . ' WHERE `id`=' . $user_id);
        $expire = time() + 7 * 24 * 60 * 60;
        $path = '/';
        $domain = '.' . Config::need('www_domain');
        $secure = false;
        $httponly = false;
        setcookie($uid_coookie_key, $user_id, $expire, $path, $domain, $secure, $httponly);
        setcookie($hash_coookie_key, $hash, $expire, $path, $domain, $secure, $httponly);
        $_COOKIE[$uid_coookie_key] = $user_id;
        $_COOKIE[$hash_coookie_key] = $hash;
        self::authorize_cookie();
    }

    public static function authorize_cookie() {
        $cookie_key = Config::need('COOKIE_KEY', 'u');
        $hash_coookie_key = $cookie_key . '_sh';
        $uid_coookie_key = $cookie_key . '_id';
        if (isset($_COOKIE[$uid_coookie_key]) && isset($_COOKIE[$hash_coookie_key])) {
            $user_id = $_COOKIE[$uid_coookie_key];
            $user = Users::getByIdLoaded($user_id);
            if (!$user)
                return false;
            if ($user->data['session'] == $_COOKIE[$hash_coookie_key]) {
                self::$id = $user_id;
                Database::query('UPDATE `user` SET `lastAccessTime`=' . time() . '  WHERE `id`=' . $user_id);
                self::$authorized = true;
                return true;
            }
        }else
            return false;
    }

}