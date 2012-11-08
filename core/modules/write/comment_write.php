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
        switch ($_POST['object_type']) {
            case Config::COMMENT_OBJECT_ALBUM_EVENT:
                $this->addEventComment();
                break;
        }
    }

    function addEventComment() {
        $parent_id = isset($_POST['parent_id']) ? (int) $_POST['parent_id'] : 0;
        $event_id = (int) $_POST['object_id'];

        $object_type = Config::COMMENT_OBJECT_ALBUM_EVENT;
        $user_id = CurrentUser::$id;
        $text = htmlspecialchars($_POST['text']);

        if ($user_id && $event_id && trim($text)) {
            $album_id = (int) Database::sql2single('SELECT album_id FROM album_events WHERE `id`=' . $event_id);
            if (!$parent_id) {
                Database::query('INSERT INTO `comments` SET
                `parent_id`=' . $parent_id . ',
                `object_type`=' . $object_type . ',
                `object_id`=' . $event_id . ',
                `user_id`=' . $user_id . ',
                `time`=' . time() . ',
                `text`=' . Database::escape($text));
                header('Location: /album/' . $album_id . '/event/' . $event_id . '#comment-' . Database::lastInsertId());
            } else {
                // parent
                $thread = Database::sql2single('SELECT `thread` FROM `comments` WHERE `id`=' . $parent_id);
                $thread = $thread ? $thread : $parent_id;
                Database::query('INSERT INTO `comments` SET
                `parent_id`=' . $parent_id . ',
                `object_type`=' . $object_type . ',
                `object_id`=' . $event_id . ',
                `user_id`=' . $user_id . ',
                `thread`=' . $thread . ',
                `time`=' . time() . ',
                `text`=' . Database::escape($text));
                header('Location: /album/' . $album_id . '/event/' . $event_id . '#comment-' . Database::lastInsertId());
            }

            Database::query('UPDATE `album_events` SET `comments_count` =
                    (SELECT COUNT(1) FROM `comments` WHERE `object_type`=' . Config::COMMENT_OBJECT_ALBUM_EVENT . ' AND `object_id`=' . $event_id . ')');
        }
    }

}