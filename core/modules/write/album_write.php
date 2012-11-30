<?php

/**
 *
 * @author mchubar
 */
class album_write extends write {

    function process() {
        switch ($_POST['action']) {
            case 'edit_event':
                $this->editEvent();
                break;
            case 'edit_album':
                $this->editAlbum();
                break;
        }
    }

    function getTemplateFields($template_id) {
        $query = 'SELECT * FROM `lib_event_templates` LET
        LEFT JOIN `lib_event_templates_fields` LETF ON LET.id=LETF.template_id
        LEFT JOIN `lib_event_templates_fields_types` LETFT ON LETFT.id=LETF.`type`
        WHERE LET.id=' . $template_id . ' ORDER BY pos';
        $data = Database::sql2array($query);

        foreach ($data as $field) {
            $out[$field['type_name']] = array(
                'type' => $field['type_name'],
                'important' => $field['important'],
                'title' => $field['title'],
                'field_id' => $field['field_id'],
                'pos' => $field['pos']
            );
        }
        return $out;
    }

    function editAlbum() {
        $error = array();
        $album_id = (int) $_POST['album_id'];
        $family = (int) isset($_POST['family']) ? $_POST['family'] : false;
        if (!$family) {
            $error['family'] = 'Кем Вы приходитесь ребёнку?';
        }

        $family = min(2, max(1, $family));


        if (isset($_FILES['cover']) && $_FILES['cover']['tmp_name']) {
            if (!$_FILES['cover']['error']) {
                $result = ImgStore::upload($_FILES['cover']['tmp_name'], Config::$sizes[Config::T_SIZE_ALBUM_COVER]);
                Database::query('UPDATE `album` SET `picture`=' . $result . ' WHERE `id`=' . $album_id);
            } else {
                $error['photo'] = 'Недопустимый формат файла';
                Site::passWrite('error_edit', $error);
                Site::passWrite('value', $_POST);
                return false;
            }
        }

        if (count($error)) {
            Site::passWrite('error_edit', $error);
            Site::passWrite('value_rdit', $_POST);
            return false;
        }

        $fields = array('child_name', 'sex', 'birthDate', 'private');
        $to_insert = array();
        foreach ($fields as $f)
            $to_insert[] = $f . '=' . Database::escape(trim(isset($_POST[$f]) ? $_POST[$f] : 0));
        if (count($to_insert))
            Database::query('INSERT INTO `album` SET `createTime`=' . time() . ',`user_id`=' . CurrentUser::$id . ', id= ' . $album_id . ',' . implode(',', $to_insert) . '
                ON DUPLICATE KEY UPDATE `updateTime`=' . time() . ',
                ' . implode(',', $to_insert));
        if (!$album_id) {
            $album_id = Database::lastInsertId();
            Database::query('INSERT INTO `user_album` SET `user_id`=' . CurrentUser::$id . ', `album_id`=' . $album_id . ', `role`=' . $family);
            Database::query('INSERT INTO `album_family` SET `album_id`=' . $album_id . ',`user_id`=' . CurrentUser::$id . ',`family_role`=' . $family . ',`accepted_time`=' . time() . ', `add_time`=' . time());
        } else {
            Database::query('UPDATE `user_album` SET `user_id`=' . CurrentUser::$id . ', `role`=' . $family . ' WHERE `album_id`=' . $album_id . '');
            Database::query('INSERT INTO `album_family` SET `album_id`=' . $album_id . ',`user_id`=' . CurrentUser::$id . ',`family_role`=' . $family . ',`accepted_time`=' . time() . ',`add_time`=' . time() . '
                ON DUPLICATE KEY UPDATE `accepted_time`=' . time() . ', `family_role`=' . $family . ' ');
        }




        header('Location: /album/' . $album_id);
    }

    function editEvent() {
        $error = array();
        $album_id = (int) $_POST['album_id'];
        if (isset($_POST['id'])) {
            $event_id = max(0, (int) $_POST['id']);
            $template_id = Database::sql2single('SELECT `template_id` FROM `album_events` AE
                JOIN `lib_events` LE ON LE.id=AE.event_id WHERE AE.`id`=' . $event_id);
        } else {
            if (isset($_POST['template_id'])) {
                $template_id = max(0, (int) $_POST['template_id']);
            }
        }
        $event_event_id = 0;
        if (isset($_POST['event_id'])) {
            $template_id = Database::sql2single('SELECT `template_id` FROM `lib_events` LE
                WHERE LE.`id`=' . (int) $_POST['event_id']);
            $event_event_id = (int) $_POST['event_id'];
        }
        if (!$template_id)
            $template_id = 1;


        $q = $q_ = array();
        Database::query('START TRANSACTION');

        if (!$event_id) {
            $event_data = Database::sql2row('SELECT * FROM `lib_events` WHERE `id`=' . (int) $event_event_id);
            if (isset($event_data['multiple']) && !$event_data['multiple']) {
                // несколько раз нельзя
                $exists = Database::sql2single('SELECT `id` FROM `album_events` WHERE `album_id`=' . $album_id . ' AND `event_id`=' . $event_data['id']);
                if ($exists)
                    throw new Exception('У Вас уже есть такое событие, и добавлять несколько копий этого события бессмысленно');
            }

            $query = 'INSERT INTO `album_events` SET id=NULL,createTime=' . time() . '';
            Badges::progressAction(CurrentUser::$id, Badges::ACTION_TYPE_ADD_EVENT);
            if ($template_id > 1)
                Badges::progressAction(CurrentUser::$id, Badges::ACTION_TYPE_ADD_THEMED_EVENT);
            Database::query($query);
            $event_id = Database::lastInsertId();
        }else {
            $check = Database::sql2single('SELECT `creator_id` FROM `album_events` WHERE `album_id`=' . $album_id . ' AND `id`=' . $event_id);
            if ((int) $check !== (int) CurrentUser::$id)
                throw new Exception('It is not your event ' . $check . ' ' . CurrentUser::$id);
        }




        $template_fields = $this->getTemplateFields($template_id);

        foreach ($template_fields as $eventName => $field) {
            if (!isset($_POST[$eventName]) || !trim($_POST[$eventName])) {
                if ($field['important'] && ($field['type'] != 'photo'))
                    $error[$eventName] = 'Обязательно к заполнению';

                if ($field['important'] && ($field['type'] == 'photo'))
                    if (!isset($_FILES[$eventName]))
                        $error[$eventName] = 'Обязательно к заполнению';
            }
            if ($field['type'] != 'photo') {
                switch ($field['type']) {
                    case 'eventTitle':
                        $q_[] = '`title`=' . Database::escape(htmlspecialchars(trim($_POST[$eventName])));
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',NULL,' . Database::escape(trim($_POST[$eventName])) . ',NULL)';
                        break;
                    case 'eventTime':
                        $_POST[$eventName] = date('Y-m-d H:i:s', strtotime($_POST[$eventName]));
                        $q_[] = '`eventTime`=' . Database::escape(htmlspecialchars(trim($_POST[$eventName])));
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',NULL,' . Database::escape(trim($_POST[$eventName])) . ',NULL)';
                        break;
                    case 'description':
                        $q_[] = '`description`=' . Database::escape(htmlspecialchars(trim($_POST[$eventName])));
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',NULL,NULL,' . Database::escape(trim($_POST[$eventName])) . ')';
                        break;
                    case'height':case'eyecolor':
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',' . Database::escape(trim($_POST[$eventName])) . ',NULL,NULL)';
                        break;
                    case 'weight':
                        $v = ($_POST[$eventName] * 1000) / 1000;
                        if ($v > 200)
                            $v = $v / 1000;
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',' . Database::escape(trim($v)) . ',NULL,NULL)';
                        break;
                    default:
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',NULL,' . Database::escape(trim($_POST[$eventName])) . ',NULL)';
                        break;
                }
            }
        }

        if (count($error)) {
            Site::passWrite('error_', $error);
            Site::passWrite('value', $_POST);
            Database::query('ROLLBACK');
            return false;
        }
        Database::query('COMMIT');
        if (count($q)) {
            $query = 'REPLACE INTO `album_events_fields`(event_id,field_id,value_int,value_varchar,value_text) VALUES ' . implode(',', $q);
            Database::query($query);
        }
        if (count($q_)) {
            $query = 'INSERT INTO `album_events` SET
                `createTime`=' . time() . ',
                `id`=' . ($event_id ? $event_id : 'NULL') . ',
                `event_id`=' . $event_event_id . ',
                `album_id`=' . $album_id . ',
                `creator_id`=' . CurrentUser::$id . ',
                ' . implode(',', $q_) . '
                    ON DUPLICATE KEY UPDATE
                `id`=' . ($event_id ? $event_id : 'NULL') . ',
                `event_id`=' . $event_event_id . ',
                `album_id`=' . $album_id . ',
                `creator_id`=' . CurrentUser::$id . ',
                    ' . implode(',', $q_) . '
                    ';
            Database::query($query);
            $event_id = $event_id ? $event_id : Database::lastInsertId();
        }

        if (isset($_FILES['photo']) && $_FILES['photo']['tmp_name']) {
            if (!$_FILES['photo']['error']) {
                $old_image_id = Database::sql2single('SELECT `picture` FROM `album_events` WHERE `id`=' . $event_id);
                $result = ImgStore::upload($_FILES['photo']['tmp_name'], Config::$sizes[Config::T_SIZE_PICTURE]);
                Database::query('UPDATE `album_events` SET `picture`=' . $result . ' WHERE `id`=' . $event_id);
                if ($old_image_id)
                    Database::query('UPDATE `images` SET `deleted`=1 WHERE `image_id`=' . $old_image_id);
                Badges::progressAction(CurrentUser::$id, Badges::ACTION_TYPE_ADD_PHOTO);
            } else {
                $error['photo'] = 'Недопустимый формат файла';
                Site::passWrite('error_', $error);
                Site::passWrite('value', $_POST);
                return false;
            }
        }

        if (isset($_FILES['photo']) && ($_FILES['photo']['error'] != 4) && $_FILES['photo']['error']) {
            $error['photo'] = 'Недопустимый формат файла';
            Site::passWrite('error_', $error);
            Site::passWrite('value', $_POST);
            return false;
        }

        header('Location: /album/' . $album_id . '/event/' . $event_id);
    }

}