<?php

class User {

    const ROLE_UNVERIFIED = 0;
    const ROLE_VERIFIED = 10;
    const ROLE_ADMIN = 20;

    public $id = false;
    public $data = false;
    public $loaded = false;

    function isAdmin() {
        return $this->data['role'] == User::ROLE_ADMIN;
    }

    function getAlbums() {
        return Database::sql2array('SELECT * FROM `album` WHERE `user_id`=' . $this->id);
    }

    function getAvatar($small = true) {
        $image_id = $this->data['avatar'];
        if ($small) {
            return ImgStore::getUrl($image_id, Config::SIZES_AVATAR_SMALL);
        } else {
            return ImgStore::getUrl($image_id, Config::SIZES_AVATAR_NORMAL);
        }
    }

    function __construct($id, $data = false) {
        $this->id = $id;
        if ($data) {
            $this->data = $data;
            $this->loade = true;
        } else {
            $this->load();
        }
    }

    function load() {
        $this->data = Database::sql2row('SELECT * FROM `user` WHERE `id`=' . $this->id);
        $this->loaded = true;
    }

}