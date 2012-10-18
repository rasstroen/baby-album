<?php

class Users {

    public static function getByIdsLoaded($uids) {
        $data = Database::sql2array('SELECT * FROM `user` WHERE `id` IN(' . implode(',', $uids) . ')');
        $users = array();
        foreach ($data as $row) {
            $users[$row['id']] = new User($row['id'], $row);
        }
        return $users;
    }

    public static function getByIdLoaded($user_id) {
        $data = Database::sql2row('SELECT * FROM `user` WHERE `id`=' . $user_id);
        if ($data) {
            return new User($user_id, $data);
        } else {
            return false;
        }
    }

}