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
                }
                break;
        }
    }

    function showProfile() {
        $user_id = array_keys(Site::$request_uri_array);
        $user_id = $user_id[1];
        if (!$user_id) {
            header('Location: /');
            exit(0);
        }
        $user = Users::getByIdLoaded($user_id);
        $out['data'] = $user->data;
        $out['user'] = $user;
        $out['albums'] = $user->getAlbums();
        return $out;
    }

}