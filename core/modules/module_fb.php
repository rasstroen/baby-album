<?php

/**
 *
 * @author mchubar
 */
class module_fb extends module {

    function _process($action, $mode) {
        switch ($action) {
            case 'show':
                switch ($mode) {
                    case 'update':
                        return $this->handleUpdate();
                        break;
                }
                break;
        }
    }

    function handleUpdate() {
        echo $_GET['hub_challenge'];
        exit(0);
    }

}