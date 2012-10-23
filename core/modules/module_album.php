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
            case 'edit':
                switch ($mode) {
                    case 'event':
                        return $this->getEditEvent();
                        break;
                    case 'item':
                        return $this->getEditAlbum();
                        break;
                }
            case 'show':
                switch ($mode) {
                    case 'event':
                        return $this->getShowEvent();
                        break;
                    case 'suggest_event':
                        return $this->getSuggestEvent();
                        break;
                }
                break;
        }
    }

    function getSuggestEvent() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        $query = 'SELECT * FROM `album` WHERE `id`=' . $album_id . ' AND `user_id`=' . CurrentUser::$id;
        $data['album'] = Database::sql2row($query);
        foreach (array('pic_small', 'pic_normal', 'pic_big', 'pic_orig') as $sizekey) {
            $sub = substr(md5($data['album'][$sizekey]), 1, 4);
            $url = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_ALBUM_COVER . '/' . $sizekey . '/' . $sub . '/' . $data['album'][$sizekey] . '.jpg';
            $data['album'][$sizekey] = $data['album'][$sizekey] ? $url : '';
        }
        if ($data['album']['user_id'] == CurrentUser::$id) {
            $data['age_days'] = getAgeInDays($data['album']['birthDate']);
            $age_end_days = $data['age_days']->days;
            $suggest = Database::sql2array('SELECT LE.id, LE.`age_start_days` , LE.`age_end_days` , LE.title, LE.template_id
FROM `lib_events` LE
WHERE `age_end_days` <' . $age_end_days . '
AND LE.id NOT
IN (
SELECT DISTINCT event_id
FROM album_events
WHERE album_id =' . $album_id . '
)
ORDER BY `age_start_days` , `age_end_days` LIMIT 4');
            $data['suggest'] = $suggest;
        }
        return $data;
    }

    function getEditAlbum() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        if (!$album_id) {
            $album_id = 0;
        }
        $query = 'SELECT * FROM `album` WHERE `id`=' . $album_id . ' AND `user_id`=' . CurrentUser::$id;
        $data['album'] = Database::sql2row($query);
        foreach (array('pic_small', 'pic_normal', 'pic_big', 'pic_orig') as $sizekey) {
            $sub = substr(md5($data['album'][$sizekey]), 1, 4);
            $url = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_ALBUM_COVER . '/' . $sizekey . '/' . $sub . '/' . $data['album'][$sizekey] . '.jpg';
            $data['album'][$sizekey] = $data['album'][$sizekey] ? $url : '';
        }
        return $data;
    }

    function getEditEvent() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }

        $event_id = array_values(Site::$request_uri_array);
        $event_id = $event_id[3];
        if (!$event_id) {
            $event_id = 0;
        }

        $opts = array(
            'where' => array(
                'AE.`album_id`=' . $album_id,
                'AE.`id`=' . $event_id,
            )
        );
        $data = $this->_list($opts);
        $event_id = isset($_GET['eid']) ? $_GET['eid'] : 0;
        if ($event_id) {
            $template_id = max(1, (int) Database::sql2single('SELECT `template_id` FROM `lib_events` WHERE `id`=' . $event_id));
        } else {
            $template_id = 1;
        }
        if (!count($data['events']))
            $data['events'] = array(
                array(
                    'template_id' => $template_id,
                    'event_id' => $event_id,
                    'album_id' => $album_id)
            );

        $ret = array('event' => array_pop($data['events']));
        return $ret;
    }

    function getShowEvent() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }

        $event_id = array_values(Site::$request_uri_array);
        $event_id = $event_id[3];
        if (!$event_id) {
            header('Location: /');
            exit(0);
        }

        $opts = array(
            'where' => array(
                'AE.`album_id`=' . $album_id,
                'AE.`id`=' . $event_id,
            )
        );
        $data = $this->_list($opts);

        return array('event' => array_pop($data['events']));
    }

    function getAlbumEvents() {

        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }
        $opts = array(
            'where' => array(
                'AE.`album_id`=' . $album_id
            )
        );
        $out = $this->_list($opts);
        $out['album'] = Database::sql2row('SELECT * FROM `album` WHERE `id`=' . $album_id);
        return $out;
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

        $query = 'SELECT SQL_CALC_FOUND_ROWS AE.*, LE.*, U.id as user_id,AE.id as id, LE.id as lib_event_id, LET.id as lib_template_id, AE.id as id
            FROM `album_events` AE
            LEFT JOIN `lib_events` LE ON LE.id=AE.event_id
            LEFT JOIN `lib_event_templates` LET ON LET.id=LE.template_id
            LEFT JOIN `user_album` UA ON UA.album_id=AE.album_id
            LEFT JOIN `user` U ON U.id=UA.user_id
WHERE (' . implode(' AND ', $where) . ')
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $events = Database::sql2array($query, 'id');
        $uids = array();

        if (count($events))
            $field_values = Database::sql2array('SELECT * FROM `album_events_fields` WHERE `event_id` IN(' . implode(',', array_keys($events)) . ')');
        else
            $field_values = array();

        foreach ($field_values as $values) {
            $events[$values['event_id']]['fields'][$values['field_id']] = $values;
        }


        foreach ($events as $event) {
            $uids[$event['user_id']] = $event['user_id'];
        }
        if (count($uids))
            $users = Users::getByIdsLoaded($uids);
        else
            $users = array();
        foreach ($events as &$event) {
            $event['user'] = isset($users[$event['user_id']]) ? $users[$event['user_id']]->data : array();
            $event['template_id'] = $event['template_id'] ? $event['template_id'] : 1;

            $sub = substr(md5($event['pic_small']), 1, 4);
            $small = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_PHOTO . '/' . 'pic_small' . '/' . $sub . '/' . $event['pic_small'] . '.jpg';
            $sub = substr(md5($event['pic_normal']), 1, 4);
            $normal = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_PHOTO . '/' . 'pic_normal' . '/' . $sub . '/' . $event['pic_normal'] . '.jpg';
            $sub = substr(md5($event['pic_big']), 1, 4);
            $big = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_PHOTO . '/' . 'pic_big' . '/' . $sub . '/' . $event['pic_big'] . '.jpg';
            $sub = substr(md5($event['pic_orig']), 1, 4);
            $orig = 'http://img.pis.ec/static/' . Config::MEDIA_TYPE_PHOTO . '/' . 'pic_orig' . '/' . $sub . '/' . $event['pic_orig'] . '.jpg';

            $event['pic_small'] = $event['pic_small'] ? $small : false;
            $event['pic_normal'] = $event['pic_normal'] ? $normal : false;
            $event['pic_big'] = $event['pic_big'] ? $big : false;
            $event['pic_orig'] = $event['pic_orig'] ? $orig : false;
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