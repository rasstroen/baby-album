<?php

class AjaxQuery {

    function __construct($method, $params, &$data) {
        return $this->$method($params, $data);
    }

    function add_album_relation() {
        $album_id = $_POST['album_id'];
        $nick = $_POST['nick'];
        $role = $_POST['role'];
        $user_id = Database::sql2single('SELECT `id` FROM `user` WHERE `nickname`=' . Database::escape($nick));
        Database::query('INSERT INTO `album_family` SET
            `album_id`=' . $album_id . ',
            `user_id`=' . $user_id . ',
            `family_role`=' . $role . ',
            `add_time`=' . time() . '
                ON DUPLICATE KEY UPDATE
             `family_role`=' . $role . '');
    }

    function add_album_relation_link() {
        $album_id = $_POST['album_id'];
        $role = $_POST['role'];
        Database::query('INSERT INTO  `album_invites` SET
            `album_id`=' . $album_id . ',
            `inviter_user_id 	`=' . CurrentUser::$id . ',
            `family_role`=' . $role);
        $uniqid = Database::lastInsertId();
        $data = array();
        $data['link'] = 'http://' . Config::need('www_domain') . '/invite/' . $album_id . '/' . $role . '/' . md5($uniqid);
        return $data;
    }

    function like() {
        $event_id = (int) $_POST['ids'];
        $plus = ($_POST['plus'] === 'true');
        if ($event_id > 0)
            if (CurrentUser::$id) {
                if ($plus) {
                    Badges::progressAction(CurrentUser::$id, Badges::ACTION_TYPE_LIKE);
                    Database::query('INSERT INTO `event_likes` SET user_id=' . CurrentUser::$id . ', event_id=' . $event_id . ', `time`=' . time() . '
                ON DUPLICATE KEY UPDATE `time`=' . time());
                }
                else
                    Database::query('DELETE FROM `event_likes` WHERE user_id=' . CurrentUser::$id . ' AND event_id=' . $event_id);
            }
    }

    function get_likes($params, &$data) {
        $ids = $_POST['ids'];
        $to_check = array();
        foreach ($ids as $event_id) {
            if (is_numeric($event_id) && $event_id > 0)
                $to_check[$event_id] = $event_id;
        }
        if (count($to_check)) {
            $res = Database::sql2array('SELECT `user_id`,`event_id` FROM `event_likes` WHERE `event_id` IN (' . implode(',', $to_check) . ')');
            $uids = array();
            foreach ($res as $row) {
                $uids[$row['user_id']] = $row['user_id'];
            }

            if (count($uids)) {
                $users = Users::getByIdsLoaded(array_keys($uids));
                foreach ($res as $row) {
                    if (isset($users[$row['user_id']])) {
                        $data['likes'][$row['event_id']][$row['user_id']] = array(
                            'nickname' => $users[$row['user_id']]->data['nickname'],
                            'id' => $users[$row['user_id']]->data['id'],
                        );
                        if (CurrentUser::$id == $row['user_id'])
                            $data['self'][$row['event_id']] = $row['user_id'];
                    }
                }
            }
        }

        foreach ($to_check as $event_id) {
            if (!isset($data['likes'][$event_id])) {
                $data['likes'][$event_id] = array();
            }
        }
        $data['owner'] = CurrentUser::$id;
        return $data;
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