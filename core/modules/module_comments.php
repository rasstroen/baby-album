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
                'AE.`album_id`=' . $album_id,
                'AE.`id`=' . $event_id,
            )
        );
        $data['comments'] = $this->_list($opts);
        return $data;
    }

    function _list() {
        $data = array();
        $has_paging = !isset($opts['no_paging']);
        $show_sortings = isset($opts['show_sortings']);
        $per_page = isset($opts['per_page']) ? $opts['per_page'] : 10;
        $per_page = min(100, max(1, (int) $per_page));

        $cond = new Conditions();
        $cond->setSorting(array('time' => array('order' => 'desc', 'title' => 'по дате')), array('time' => array('order' => 'desc', 'title' => 'по дате')));
        $cond->setPaging(100000, $per_page);

        $where = array('1');
        if (isset($opts['where']))
            foreach ($opts['where'] as $w)
                $where[] = $w;
        $order = $cond->getSortingField() . ' ' . $cond->getSortingOrderSQL();
        $limit = $cond->getLimit();

        $query = 'SELECT * FROM `comments`
WHERE (' . implode(' AND ', $where) . ')
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $events = Database::sql2array($query, 'id');

        return $data;
    }

}