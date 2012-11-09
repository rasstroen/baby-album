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
        $old = Database::sql2row('SELECT pic_small,pic_normal,pic_big,pic_orig FROM `album` WHERE `id`=' . $album_id);
        if ($old)
            list($small, $normal, $big, $orig) = array_values($old);
        else
            list($small, $normal, $big, $orig) = array(false, false, false, false);
        if (isset($_FILES['cover']) && $_FILES['cover']['tmp_name']) {
            if (!$_FILES['cover']['error']) {
                $result = ImageStore::store($_FILES['cover']['tmp_name'], array('pic_small' => '100x100x0', 'pic_normal' => '200x200x0', 'pic_big' => '450x450x1', 'pic_orig' => '0x0x1'), Config::MEDIA_TYPE_ALBUM_COVER
                                , array($small, $normal, $big, $orig));
                foreach ($result['result']['file'] as $key => $file) {
                    if (isset($file['ID'])) {
                        $id = $file['ID'];
                        Database::query('UPDATE `album` SET `' . $key . '`=' . $id . ' WHERE `id`=' . $album_id);
                    }
                }
            } else {
                $error['photo'] = 'Недопустимый формат файла';
                Site::passWrite('error_edit', $error);
                Site::passWrite('value', $_POST);
                return false;
            }
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
            Database::query('INSERT INTO `user_album` SET `user_id`=' . CurrentUser::$id . ', `album_id`=' . $album_id);
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

            $query = 'INSERT INTO `album_events` SET id=NULL';
            Database::query($query);
            $event_id = Database::lastInsertId();
        }else{
            $check = Database::sql2single('SELECT `creator_id` FROM `album_events` WHERE `album_id`=' . $album_id . ' AND `id`=' . $event_id);
            if((int)$check !== (int)CurrentUser::$id)
                throw new Exception('It is not your event '.$check.' '.CurrentUser::$id);
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
                    case 'weight':case'height':case'eyecolor':
                        $q[] = '(' . $event_id . ',' . $field['field_id'] . ',' . Database::escape(trim($_POST[$eventName])) . ',NULL,NULL)';
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
                `createTime`=' . time() . ',
                `id`=' . ($event_id ? $event_id : 'NULL') . ',
                `event_id`=' . $event_event_id . ',
                `album_id`=' . $album_id . ',
                `creator_id`=' . CurrentUser::$id . ',
                    ' . implode(',', $q_) . '
                    ';
            Database::query($query);
            $event_id = $event_id ? $event_id : Database::lastInsertId();
        }

        $old = Database::sql2row('SELECT pic_small,pic_normal,pic_big,pic_orig FROM `album_events` WHERE `id`=' . $event_id);
        list($small, $normal, $big, $orig) = array_values($old);
        if (isset($_FILES['photo']) && $_FILES['photo']['tmp_name']) {
            if (!$_FILES['photo']['error']) {
                $result = ImageStore::store($_FILES['photo']['tmp_name'], array('pic_small' => '200x200x0', 'pic_normal' => '450x450x1', 'pic_big' => '1680x1680x1', 'pic_orig' => '0x0x1'), Config::MEDIA_TYPE_PHOTO
                                , array($small, $normal, $big, $orig));
                foreach ($result['result']['file'] as $key => $file) {
                    if (isset($file['ID'])) {
                        $id = $file['ID'];
                        Database::query('UPDATE `album_events` SET `' . $key . '`=' . $id . ' WHERE `id`=' . $event_id);
                    }
                }
            } else {
                $error['photo'] = 'Недопустимый формат файла';
                Site::passWrite('error_', $error);
                Site::passWrite('value', $_POST);
                return false;
            }
        }

        header('Location: /album/' . $album_id . '/event/' . $event_id);
    }

}