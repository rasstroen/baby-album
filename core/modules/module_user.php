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
                    case 'connect_fb':
                        return $this->showConnectFb();
                        break;
                    case 'connect_ok':
                        return $this->showConnectOk();
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

    function showConnectFb() {
        $code = isset($_GET['code']) ? $_GET['code'] : false;
        if (!$code) {
            $data['error'] = 'Неудачная попытка авторизации';
        } else {
            // getting token
            $url = "https://graph.facebook.com/oauth/access_token?"
                    . "client_id=" . Config::APP_ID_FB . "&redirect_uri=" . urlencode('http://balbum.ru/connect/fb')
                    . "&client_secret=" . Config::APP_SECRET_FB . "&code=" . $code;
            $s = file_get_contents($url);
            parse_str($s, $data);
            if (isset($data['access_token'])) {
                //got access_token
                Database::query('UPDATE `user` SET
                    `fb_access_token`=' . Database::escape($data['access_token']) . ',
                    `fb_access_token_expire`=' . (time() + $data['expires']) . '
                     WHERE `id`=' . CurrentUser::$id);
                $data['success'] = true;
                // ask vk api for user name
                $url = 'https://graph.facebook.com/me/?access_token=' . $data['access_token'];
                $udata = json_decode(file_get_contents($url), true);
                if ($udata) {
                    Database::query('UPDATE `user` SET
                    `fb_id`=' . Database::escape($udata['id']) . ' WHERE `id`=' . CurrentUser::$id);
                    $data['name'] = $udata['name'];
                    $data['pic'] = 'https://graph.facebook.com/' . ((isset($udata['username']) && $udata['username']) ? $udata['username'] : $udata['id']) . '/picture';
                    $user = Users::getByIdLoaded(CurrentUser::$id);
                    // if no any avatar, set vk avatar as site avatar
                    if (!$user->data['avatar'] && $data['pic']) {
                        $tmp_name = '/tmp/' . md5(time() . CurrentUser::$id);
                        file_put_contents($tmp_name, file_get_contents($data['pic']));
                        $result = ImgStore::upload($tmp_name, Config::$sizes[Config::T_SIZE_AVATAR]);
                        if ($result)
                            Database::query('UPDATE `user` SET `avatar`=' . $result . ' WHERE `id`=' . CurrentUser::$id);
                    }
                    Database::query('UPDATE `user` SET `fb_name`=' . Database::escape($data['name']) . ' WHERE `id`=' . CurrentUser::$id);
                }
            }
        }
        return $data;
    }

    function showConnectVk() {
        $code = isset($_GET['code']) ? $_GET['code'] : false;
        if (!$code) {
            $data['error'] = 'Неудачная попытка авторизации';
        } else {
            // getting token
            $url = 'https://oauth.vk.com/access_token?client_id=' . Config::APP_ID_VK . '&client_secret=' . Config::APP_SECRET_VK . '&code=' . $code . '&redirect_uri=http://balbum.ru/connect/vk';
            $data = json_decode(@file_get_contents($url), true);
            if (isset($data['access_token'])) {
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
                        if ($result)
                            Database::query('UPDATE `user` SET `avatar`=' . $result . ' WHERE `id`=' . CurrentUser::$id);
                    }
                    Database::query('UPDATE `user` SET `vk_name`=' . Database::escape($data['name']) . ' WHERE `id`=' . CurrentUser::$id);
                }
            }
        }
        return $data;
    }

    function showConnectOk() {
        $code = isset($_GET['code']) ? $_GET['code'] : false;
        if (!$code) {
            $out['error'] = 'Неудачная попытка авторизации';
        } else {
            // getting token
            $postdata = http_build_query(
                    array(
                        'grant_type' => 'authorization_code',
                        'client_id' => Config::APP_ID_OK,
                        'client_secret' => Config::APP_SECRET_OK,
                        'code' => $code,
                        'redirect_uri' => 'http://balbum.ru/connect/ok',
                    )
            );

            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );

            $context = stream_context_create($opts);
            $data = json_decode(file_get_contents('http://api.odnoklassniki.ru/oauth/token.do', false, $context), true);
            if (isset($data['access_token'])) {
                //got access_token
                Database::query('UPDATE `user` SET
                    `ok_access_token`=' . Database::escape($data['access_token']) . ',
                    `ok_refresh_token`=' . Database::escape($data['refresh_token']) . ',
                    `ok_access_token_expire`=' . (time()) . '
                     WHERE `id`=' . CurrentUser::$id);
                $out['success'] = true;
                // ask vk api for user name
                $method_url = 'http://api.odnoklassniki.ru/fb.do?client_id=' . Config::APP_ID_OK . '&access_token=' . $data['access_token'] . '&application_key=' . Config::APP_KEY_OK . '&method=users.getCurrentUser&sig=' . md5('application_key=' . Config::APP_KEY_OK . 'client_id=' . Config::APP_ID_OK . 'method=users.getCurrentUser' . md5($data['access_token'] . Config::APP_SECRET_OK));
                $udata = json_decode(file_get_contents($method_url), true);


                if ($udata) {
                    Database::query('UPDATE `user` SET
                    `ok_id`=' . Database::escape($udata['uid']) . ' WHERE `id`=' . CurrentUser::$id);
                    $out['name'] = $udata['name'];
                    $out['pic'] = str_replace('photoType=4', 'photoType=6', $udata['pic_1']);
                    $user = Users::getByIdLoaded(CurrentUser::$id);
                    // if no any avatar, set vk avatar as site avatar
                    if (!$user->data['avatar'] && $out['pic']) {
                        $tmp_name = '/tmp/' . md5(time() . CurrentUser::$id);
                        file_put_contents($tmp_name, file_get_contents($out['pic']));
                        $result = ImgStore::upload($tmp_name, Config::$sizes[Config::T_SIZE_AVATAR]);
                        if ($result)
                            Database::query('UPDATE `user` SET `avatar`=' . $result . ' WHERE `id`=' . CurrentUser::$id);
                    }
                    Database::query('UPDATE `user` SET `ok_name`=' . Database::escape($out['name']) . ' WHERE `id`=' . CurrentUser::$id);
                }
            }else
                $out['error'] = 'Неудачная попытка авторизации';
        }
        return $out;
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