<?php

/**
 *
 * @author mchubar
 */
class module_user extends module {

    function _process($action, $mode) {
        switch ($action) {
            case 'show':
                switch ($mode) {
                    case 'profile':
                        return $this->showProfile();
                        break;
                    case 'connect_vk':
                        return $this->showConnectVk();
                        break;
                    case 'forget':
                        return $this->showForget();
                        break;
                    case 'profile_small':
                        return $this->showProfile();
                        break;
                    case 'confirmation':
                        return $this->showConfirmation();
                        break;
                    case 'pass_restore':
                        return $this->showPassRestore();
                        break;
                    case 'static_auth':
                        return $this->showStaticAuth();
                        break;
                    case 'points':
                        return $this->showPoints();
                        break;
                    case 'badges':
                        return $this->showBadges();
                        break;
                }
                break;
        }
    }

    function showConnectVk() {
        $code = isset($_GET['code']) ? $_GET['code'] : false;
        if (!$code) {
            $data['error'] = 'Неудачная попытка авторизации';
        } else {
            // getting token
            $url = 'https://oauth.vk.com/access_token?client_id=' . Config::APP_ID_VK . '&client_secret=' . Config::APP_SECRET_VK . '&code=' . $code . '&redirect_uri=http://balbum.ru/connect/vk';
            $data = json_decode(@file_get_contents($url), true);
            if ($data['access_token']) {
                //got access_token
                Database::query('UPDATE `user` SET 
                    `vk_access_token`=' . Database::escape($data['access_token']) . ',
                    `vk_access_token_expire`=' . (time() + $data['expires_in']) . ',
                    `vk_id`=' . $data['user_id'] . ' WHERE `id`=' . CurrentUser::$id);
                $data['success'] = true;
                // ask vk api for user name
                $url = 'https://api.vk.com/method/users.get?uids=' . $data['user_id'] . '&fields=uid,first_name,last_name,nickname,screen_name,photo,photo_medium,photo_big&access_token=' . $data['access_token'];

                $udata = json_decode(file_get_contents($url), true);
                if ($udata) {
                    $data['name'] = $udata['response'][0]['first_name'] . ' ' . $udata['response'][0]['last_name'];
                    $data['pic'] = $udata['response'][0]['photo_medium'];
                    $user = Users::getByIdLoaded(CurrentUser::$id);
                    // if no any avatar, set vk avatar as site avatar
                    if (!$user->data['avatar'] && $data['pic']) {
                        $tmp_name = '/tmp/' . md5(time() . CurrentUser::$id);
                        file_put_contents($tmp_name, file_get_contents($data['pic']));
                        $result = ImgStore::upload($tmp_name, Config::$sizes[Config::T_SIZE_AVATAR]);
                        Database::query('UPDATE `user` SET `avatar`=' . $result . ' WHERE `id`=' . CurrentUser::$id);
                    }
                    Database::query('UPDATE `user` SET `vk_name`=' . Database::escape($data['name']) . ' WHERE `id`=' . CurrentUser::$id);
                }
            }
        }
        return $data;
    }

    function showBadges() {
        $user_id = array_values(Site::$request_uri_array);
        $user_id = $user_id[1];
        if (!$user_id) {
            header('Location: /');
            exit(0);
        }

        $user = Users::getByIdLoaded($user_id);

        $data = array('badges' => array());
        $data['badges'] = Badges::getUserAllBadges($user_id);
        return $data;
    }

    function showForget() {

    }

    function showStaticAuth() {
        $data = array();
        if (CurrentUser::$authorized) {
            $notifies = CurrentUser::getNotifies();
            if ($notifies)
                $data['notify'] = $notifies;
        }
        return $data;
    }

    function showPassRestore() {
        $hash = array_pop(Site::$request_uri_array);
        list($id, $hash) = explode('-', $hash);
        $data['success'] = false;
        if ($hash) {
            $success = Database::sql2single('SELECT id FROM `user` WHERE `id`=' . $id . ' AND (`hash`=\'changing\' OR `hash`=' . Database::escape($hash) . ')');
            if ($success) {
                CurrentUser::set_cookie($success);
                Database::query('UPDATE `user` SET hash=\'changing\' WHERE id=' . $success);
                $data['success'] = true;
            }
        }
        return $data;
    }

    function showConfirmation() {
        $hash = array_pop(Site::$request_uri_array);
        list($id, $hash) = explode('-', $hash);
        $data['success'] = false;
        if ($hash) {
            $success = Database::sql2single('SELECT id FROM `user` WHERE `id`=' . $id . ' AND `hash`=' . Database::escape($hash));
            if ($success) {
                CurrentUser::set_cookie($success);
                Database::query('UPDATE `user` SET hash=\'\', `role`=' . User::ROLE_VERIFIED . ' WHERE id=' . $success);
                $data['success'] = true;
            }
        }
        return $data;
    }

    function showPoints() {
        $user_id = array_values(Site::$request_uri_array);
        $user_id = $user_id[1];
        if (!$user_id) {
            header('Location: /');
            exit(0);
        }
        if ($user_id != CurrentUser::$id) {
            header('Location: /');
            exit(0);
        }
        $user = Users::getByIdLoaded($user_id);
        $out['data'] = $user->data;
        Site::passTitle($user->data['nickname'] . ' — бонусы пользователя');
        $out['history'] = Database::sql2array('SELECT * FROM `user_points_log` WHERE `user_id`=' . $user_id . ' ORDER BY `time` DESC');
        return $out;
    }

    function showProfile() {
        $user_id = array_values(Site::$request_uri_array);
        $user_id = $user_id[1];
        if (!$user_id) {
            header('Location: /');
            exit(0);
        }
        $user = Users::getByIdLoaded($user_id);
        $out['data'] = $user->data;
        $out['user'] = $user;
        $out['albums'] = $user->getAlbums();

        Site::passTitle($user->data['nickname'] . ' — профиль пользователя');

        return $out;
    }

}