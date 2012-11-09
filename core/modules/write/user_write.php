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
            'nickname' => '/[a-zA-Zа-яА-ЯёЁь0-9]+$/isU',
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
            if (count($to_update)) {
                try {
                    Database::query('UPDATE `user` SET ' . implode(',', $to_update) . ' WHERE `id`=' . CurrentUser::$id);
                } catch (Exception $e) {
                    $error['nickname'] = 'Никгейм занят. Попробуйте придумать другой';
                    Site::passWrite('error_edit', $error);
                    Site::passWrite('value_edit', $_POST);
                    return;
                }
            }
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
                Site::passWrite('value_auth', $_POST);
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
                $data['nickname'] = $this->getUniqueNickname(strtolower(trim($_POST['nickname'])), $_POST['email']);
                $data['password'] = md5(strtolower(trim($_POST['password'])));
                $data['registerTime'] = time();
                $data['role'] = User::ROLE_UNVERIFIED;
                $data['hash'] = md5(time() . '-' . rand(1, 10));
                foreach ($data as $f => $v) {
                    $fields[] = '`' . $f . '`=' . Database::escape($v);
                }

                Database::query('INSERT INTO `user` SET ' . implode(',', $fields));
                $uid = Database::lastInsertId();
                try {
                    Site::passWrite('success', true);
                } catch (Exception $e) {
                    $error['email'] = $e->getMessage();
                    Site::passWrite('error_register', $error);
                    return;
                }
                $this->sendRegisterEmail($data['email'], '', $uid . '-' . $data['hash']);
            } catch (Exception $e) {
                $error['email'] = 'E-mail уже используется, укажите другой';
                Site::passWrite('error_register', $error);
                return;
            }
            CurrentUser::set_cookie($uid);
        }
    }

    function sendRegisterEmail($email, $body, $hash) {
        $body = '<h2>Поздравляем с успешной регистрацией на сайте balbum.ru</h2>';
        $body .= 'Пожалуйста, пройдите по ссылке <a href="http://balbum.ru/c/' . $hash . '">http://balbum.ru/c/' . $hash . '</a> для подтверждения Вашего почтового адреса.';
        $body = '<!DOCTYPE html PUBLIC "html">
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            </head>
            <body>' . $body . '</body>
        </html>';
        $mailer = new MailSender();
        $r = $mailer->mail(Config::GLOBAL_EMAIL, $email, 'Регистрация на balbum.ru', $body);
        if (!$r)
            throw new Exception('mailing error');
        return $r;
    }

    function getUniqueNickname($nickname, $email) {
        if (!$nickname)
            $nickname = str_replace('.', '_', str_replace('@', '-', array_shift(explode('@', $email))));
        $query = 'SELECT COUNT(1) FROM `user` WHERE `nickname`=' . Database::escape($nickname) . '';
        if (!Database::sql2single($query))
            return $nickname;
        else
            return $nickname . substr(time(), 5, 5) . rand(10, 20);
    }

}