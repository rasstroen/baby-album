<?php

/**
 *
 * @author mchubar
 */
class comment_write extends write {

    function process() {
        switch ($_POST['action']) {
            case 'add_comment':
                $this->addComment();
                break;
        }
    }

    function addComment() {
        dpr($_POST);
    }

}