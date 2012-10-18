<?php

/**
 *
 * @author mchubar
 */
class user_write extends write {

    function process() {
        switch ($_POST['action']) {
            case 'register':
                $this->register();
                break;
            case 'auth':
                $this->auth();
                break;
            case 'edit':
                $this->edit();
                break;
        }
    }

    function edit() {
        if (isset($_FILES['userpic']) && !$_FILES['userpic']['error']) {
            $result = ImageStore::store($_FILES['userpic']['tmp_name'], array('avatar_small' => '50x50x0', 'avatar_normal' => '100x100x0',), Config::MEDIA_TYPE_AVATAR);
            foreach ($result['result']['file'] as $key => $file) {
                if (isset($file['ID'])) {
                    $id = $file['ID'];
                    Database::query('UPDATE `user` SET `' . $key . '`=' . $id . ' WHERE `id`=' . CurrentUser::$id);
                }
            }
        }

        $fields_editable = array(
            'first_name' => '/[a-zA-Zа-яА-ЯёЁь]+$/isU',
            'last_name' => '/[a-zA-Zа-яА-ЯёЁь]+$/isU',
            'middle_name' => '/[a-zA-Zа-яА-ЯёЁь]+$/isU',
            'nickname' => '/[a-zA-Zа-яА-ЯёЁь]+$/isU',
        );

        $error = array();

        foreach ($fields_editable as $fieldname => $pattern) {
            if (isset($_POST[$fieldname])) {
                if (preg_match($pattern, trim($_POST[$fieldname]))) {
                    $to_update[] = $fieldname . '=' . Database::escape(trim($_POST[$fieldname]));
                } else {
                    $error[$fieldname] = 'Неправильный формат';
                }
            }
        }
        if (count($error)) {
            Site::passWrite('error_edit', $error);
            Site::passWrite('value_edit', $_POST);
            return;
        } else {
            dpr($to_update);
        }
    }

    function auth() {
        $error = array();
        if (!valid_email_address($_POST['email']))
            $error['email'] = 'неправильный E-mail';
        if (!trim($_POST['password']))
            $error['password'] = 'Слишком короткий пароль';

        if (count($error)) {
            Site::passWrite('error_auth', $error);
            return;
        } else {
            $email = strtolower(trim($_POST['email']));
            $password = md5(strtolower(trim($_POST['password'])));
            $user_id = Database::sql2single('SELECT `id` FROM `user` WHERE `email`=' . Database::escape($email) . ' AND `password`=' . Database::escape($password));
            if ($user_id) {
                CurrentUser::set_cookie($user_id);
            } else {
                $error['password'] = 'Неверный пароль';
                Site::passWrite('error_auth', $error);
                return;
            }
        }
    }

    function register() {
        $error = array();
        if (!valid_email_address($_POST['email']))
            $error['email'] = 'неправильный E-mail';
        if (!trim($_POST['password']))
            $error['password'] = 'Слишком короткий пароль';

        if (count($error)) {
            Site::passWrite('error_register', $error);
            return;
        } else {
            try {
                $fields = array();
                $data['email'] = strtolower(trim($_POST['email']));
                $data['password'] = md5(strtolower(trim($_POST['password'])));
                $data['registerTime'] = time();
                $data['role'] = User::ROLE_UNVERIFIED;
                foreach ($data as $f => $v) {
                    $fields[] = '`' . $f . '`=' . Database::escape($v);
                }
                Database::query('INSERT INTO `user` SET ' . implode(',', $fields));
            } catch (Exception $e) {
                $error['email'] = 'E-mail уже используется, укажите другой';
                Site::passWrite('error_register', $error);
                return;
            }
            CurrentUser::set_cookie(Database::lastInsertId());
        }
    }

}