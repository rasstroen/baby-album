<?php

class AjaxQuery {

    function __construct($method, $params, $data) {
        return $this->$method($params, $data);
    }

    function album_hide_suggest($params, &$data) {
        $event_id = (int) $_POST['event_id'];
        $album_id = (int) $_POST['album_id'];
        Database::query('REPLACE INTO `user_suggest_inactive` SET `album_id`=' . $album_id . ', `event_id`=' . $event_id);
        return $data;
    }

    function album_show_suggest($params, &$data) {
        $event_id = (int) $_POST['event_id'];
        $album_id = (int) $_POST['album_id'];
        Database::query('DELETE FROM `user_suggest_inactive` WHERE `album_id`=' . $album_id . ' AND `event_id`=' . $event_id);
        return $data;
    }

}