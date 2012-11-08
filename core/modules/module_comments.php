<?php

/**
 *
 * @author mchubar
 */
class module_comments extends module {

    function _process($action, $mode) {
        switch ($action) {
            case 'list':
                switch ($mode) {
                    case 'album_event':
                        return $this->listAlbumEventComments();
                        break;
                }
                break;
        }
    }

    function listAlbumEventComments() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }

        $event_id = array_values(Site::$request_uri_array);
        $event_id = (int) $event_id[3];
        if (!$event_id) {
            $event_id = 0;
        }
        if (!$event_id)
            return;


        $data['object_id'] = $event_id;

        $opts = array(
            'where' => array(
                '`object_id`=' . $event_id,
                '`object_type`=' . Config::COMMENT_OBJECT_ALBUM_EVENT,
            )
        );
        $data['comments'] = $this->_list($opts);
        return $data;
    }

    function _list($opts) {
        $data = array();
        $has_paging = !isset($opts['no_paging']);
        $show_sortings = isset($opts['show_sortings']);
        $per_page = isset($opts['per_page']) ? $opts['per_page'] : 10;
        $per_page = min(100, max(1, (int) $per_page));

        $cond = new Conditions();
        $cond->setSorting(array('time' => array('order' => 'desc', 'title' => 'по дате')), array('time' => array('order' => 'desc', 'title' => 'по дате')));
        $cond->setPaging(100000, $per_page);

        $where = array('parent_id=0');
        if (isset($opts['where']))
            foreach ($opts['where'] as $w)
                $where[] = $w;
        $order = $cond->getSortingField() . ' ' . $cond->getSortingOrderSQL();
        $limit = $cond->getLimit();

        $query = 'SELECT * FROM `comments`
WHERE (' . implode(' AND ', $where) . ')
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $comments = Database::sql2array($query, 'id');
        $pids = array();
        $uids = array();
        foreach ($comments as $comment) {
            $pids[$comment['id']] = $comment['id'];
            $uids[$comment['user_id']] = $comment['user_id'];
        }




        if (count($pids)) {
            $query = 'SELECT * FROM `comments` WHERE `thread` IN (' . implode(',', $pids) . ') ORDER BY `thread`,`id`';
            $nextlevel = Database::sql2array($query, 'id');
            $comments+=$nextlevel;

            foreach ($comments as $comment) {
                $uids[$comment['user_id']] = $comment['user_id'];
            }

            if (count($uids))
                $users = Users::getByIdsLoaded($uids);
            else
                $users = array();

            foreach ($comments as &$comment) {
                if (!isset($users[$comment['user_id']]))
                    continue;
                $comment['user'] = $users[$comment['user_id']];
                $parents[$comment['parent_id']][$comment['id']] = $comment;
                uasort($parents[$comment['parent_id']], 'x_sort_comment');
            }


            $comments = $this->build_tree($parents, 0);
        }

        return $comments;
    }

    function build_tree($comments, $parent_id, $level = 0, &$result = array()) {
        if (is_array($comments) && isset($comments[$parent_id]) && count($comments[$parent_id]) > 0) {
            foreach ($comments[$parent_id] as $cat) {
                $result[$cat['id']] = $cat;
                $result[$cat['id']]['childs'] = array();
                $this->build_tree($comments, $cat['id'], $level + 1, $result[$cat['id']]['childs']);
            }
        }
        else
            return null;
        return $result;
    }

}

function x_sort_comment($a, $b) {
    return $a['id'] > $b['id'] ? 1 : -1;
}