<?php

/**
 *
 * @author mchubar
 */
class module_album extends module {

    function process($action, $mode) {
        switch ($action) {
            case 'list':
                switch ($mode) {
                    case 'events':
                        return $this->getAlbumEvents();
                        break;
                }
                break;
        }
    }

    function getAlbumEvents() {

        $album_id = array_keys(Site::$request_uri_array);
        $album_id = $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }
        $opts = array(
            'where' => array(
                'AE.`album_id`=' . $album_id
            )
        );
        return $this->_list($opts);
    }

    function _list($opts = array()) {

        $has_paging = !isset($opts['no_paging']);
        $show_sortings = isset($opts['show_sortings']);
        $per_page = isset($opts['per_page']) ? $opts['per_page'] : 10;
        $per_page = min(100, max(1, (int) $per_page));

        $cond = new Conditions();
        $cond->setSorting(array('eventTime' => array('order' => 'desc', 'title' => 'по дате')));
        $cond->setPaging(100000, $per_page);

        $where = array('1');
        if (isset($opts['where']))
            foreach ($opts['where'] as $w)
                $where[] = $w;
        $order = $cond->getSortingField() . ' ' . $cond->getSortingOrderSQL();
        $limit = $cond->getLimit();

        $query = 'SELECT SQL_CALC_FOUND_ROWS AE.*, LE.*, U.id as user_id,AE.id as id, LE.id as lib_event_id, LET.id as lib_template_id, AE.id as id FROM `album_events` AE
            LEFT JOIN `lib_events` LE ON LE.id=AE.event_id
            LEFT JOIN `lib_event_templates` LET ON LET.id=LE.template_id
            LEFT JOIN `user_album` UA ON UA.album_id=AE.album_id
            LEFT JOIN `user` U ON U.id=UA.user_id
WHERE (' . implode(' AND ', $where) . ')
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $events = Database::sql2array($query, 'id');
        $uids = array();


        foreach ($events as $event) {
            $uids[$event['user_id']] = $event['user_id'];
        }
        if (count($uids))
            $users = Users::getByIdsLoaded($uids);
        else
            $users = array();
        foreach ($events as &$event) {
            $event['user'] = isset($users[$event['user_id']]) ? $users[$event['user_id']]->data : array();
        }



        $cond->setPaging(Database::sql2single('SELECT FOUND_ROWS()'), $per_page);

        $data['events'] = $events;
        $data['conditions'] = $cond->getConditions();
        if (!$show_sortings) {
            foreach ($data['conditions'] as $key => $group) {
                if ($group['mode'] == 'sorting') {
                    unset($data['conditions'][$key]);
                }
            }
        }

        return $data;
    }

}