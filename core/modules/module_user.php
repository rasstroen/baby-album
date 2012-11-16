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
                }
                break;
        }
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
            $success = Database::sql2single('SELECT id FROM `user` WHERE `id`=' . $id . ' AND (`hash`=\'changing\' OR `hash`=' . Database::escape($hash).')');
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