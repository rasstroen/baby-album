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
                    case 'main':
                        return $this->getAlbumMainEvents();
                        break;
                    case 'list_of_event': // все эвенты такого типа
                        return $this->getAlbumTypedEvents();
                        break;
                    case 'suggested_events':
                        return $this->getAlbumSuggestedEvents();
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

    function getAlbumSuggestedEvents() {
        $per_page = 10;
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        $query = 'SELECT * FROM `album` WHERE `id`=' . $album_id . ' AND `user_id`=' . CurrentUser::$id;
        $data['album'] = Database::sql2row($query);
        $image_id = $data['album']['picture'];
        foreach (Config::$sizes[Config::T_SIZE_ALBUM_COVER] as $size => $dimensions)
            $data['album']['picture_' . $size] = ImgStore::getUrl($image_id, $size);


        $cond = new Conditions();
        $cond->setPaging(99999, $per_page);


        $data['age_days'] = getAgeInDays($data['album']['birthDate']);
        $age_end_days = $data['age_days']->days;
        $suggest = Database::sql2array('SELECT SQL_CALC_FOUND_ROWS LE.id, LE.`age_start_days` , LE.`age_end_days` ,LE.multiple, LE.title, LE.template_id, LE.description
FROM `lib_events` LE
ORDER BY `age_start_days` , `age_end_days` LIMIT ' . $cond->getLimit());
        $eid = array();
        foreach ($suggest as $suggest_) {
            $eid[$suggest_['id']] = $suggest_['id'];
        }
        $total_count = Database::sql2single('SELECT FOUND_ROWS()');
        $cond->setPaging($total_count, $per_page);
        if (count($eid)) {
            $query = 'SELECT `event_id`,`id` FROM `album_events` WHERE `album_id`=' . $album_id . ' AND `event_id` IN(' . implode(',', $eid) . ')';
            $data['exists'] = Database::sql2array($query, 'event_id');
        }
        $query = 'SELECT `event_id`,`album_id` FROM `user_suggest_inactive` WHERE `album_id`=' . $album_id;
        $data['hidden'] = Database::sql2array($query, 'event_id');

        $data['count'] = $total_count;
        $data['conditions'] = $cond->getConditions();
        $data['suggest'] = $suggest;

        return $data;
    }

    function getSuggestEvent() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        $query = 'SELECT * FROM `album` WHERE `id`=' . $album_id;
        $data['album'] = Database::sql2row($query);

        if (!$data['album'])
            throw new Exception('Нет такого альбома');

        foreach (array('pic_small', 'pic_normal', 'pic_big', 'pic_big') as $sizekey) {
            $sizes = array(
                'pic_small' => Config::SIZES_ALBUM_COVER_SMALL,
                'pic_normal' => Config::SIZES_ALBUM_COVER_NORMAL,
                'pic_big' => Config::SIZES_ALBUM_COVER_BIG,
                'pic_orig' => 0,
            );

            $data['album'][$sizekey] = $data['album']['picture'] ? ImgStore::getUrl($data['album']['picture'], $sizes[$sizekey]) : '';
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
AND LE.id NOT
IN (
SELECT DISTINCT event_id
FROM `user_suggest_inactive`
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
        if ($data['album']) {
            foreach (array('pic_small', 'pic_normal', 'pic_big', 'pic_orig') as $sizekey) {
                $sizes = array(
                    'pic_small' => Config::SIZES_ALBUM_COVER_SMALL,
                    'pic_normal' => Config::SIZES_ALBUM_COVER_NORMAL,
                    'pic_big' => Config::SIZES_ALBUM_COVER_BIG,
                    'pic_orig' => 0,
                );
                $data['album'][$sizekey] = $data['album']['picture'] ? ImgStore::getUrl($data['album']['picture'], $sizes[$sizekey]) : '';
            }
        }
        $data['album']['family'] = Database::sql2single('SELECT `family_role` FROM `album_family` WHERE `album_id`=' . $album_id . ' and `user_id`=' . CurrentUser::$id);
        $family = Database::sql2array('SELECT * FROM `album_family` WHERE `album_id`=' . $album_id);
        $data['family'] = array();
        foreach ($family as $row) {
            $data['family'][$row['user_id']]['user'] = Users::getByIdLoaded($row['user_id']);
            $data['family'][$row['user_id']]['role'] = $row['family_role'];
            $data['family'][$row['user_id']]['role_title'] = Config::$family[$row['family_role']];
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
        $event_id = (int) $event_id[3];
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
        if (!$event_id) {
            $event_id = array_values(Site::$request_uri_array);
            $event_id = (int) $event_id[4];
        }
        if ($event_id) {
            $event_data = Database::sql2row('SELECT * FROM `lib_events` WHERE `id`=' . $event_id);
            $template_id = max(1, $event_data['template_id']);
        } else {
            $template_id = 1;
            $event_data = array('title' => '');
        }
        if (!count($data['events'])) {
            // создание эвента
            // а можно несколько раз такой эвент создавать?
            if (isset($event_data['multiple']) && !$event_data['multiple']) {
                // несколько раз нельзя
                $exists = Database::sql2single('SELECT `id` FROM `album_events` WHERE `album_id`=' . $album_id . ' AND `event_id`=' . $event_data['id']);
                if ($exists)
                    throw new Exception('Уже есть такое событие');
            }

            $data['events'] = array(
                array(
                    'event_title' => $event_data['title'],
                    'template_id' => $template_id,
                    'event_id' => $event_id,
                    'album_id' => $album_id)
            );
        }

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

    function getAlbumTypedEvents() {
        $event_id = array_values(Site::$request_uri_array);
        $event_id = (int) $event_id[1];
        if (!$event_id) {
            header('Location: /');
            exit(0);
        }

        $event = Database::sql2row('SELECT * FROM `lib_events` WHERE `id`=' . $event_id);
        if ($event) {

            $opts = array(
                'where' => array(
                    'AE.`is_public`=1',
                    'AE.`event_id`=' . $event_id
                )
            );
            Site::passTitle('«' . $event['title'] . '» лента событий');
            $out = $this->_list($opts);
        }
        $out['event'] = $event;
        return $out;
    }

    function getAlbumMainEvents() {
        $opts = array(
            'where' => array(
                'AE.`is_public`=1'
            )
        );
        $out = $this->_list($opts);
        return $out;
    }

    function getAlbumEvents() {
        $album_id = array_values(Site::$request_uri_array);
        $album_id = (int) $album_id[1];
        if (!$album_id) {
            header('Location: /');
            exit(0);
        }
        $atime = isset($_GET['atime']) && $_GET['atime'];

        $opts = array(
            'where' => array(
                'AE.`album_id`=' . $album_id
            ),
            'historical' => !$atime,
        );
        $out = $this->_list($opts);
        $out['album'] = Database::sql2row('SELECT * FROM `album` WHERE `id`=' . $album_id);
        Site::passTitle('Альбом "' . $out['album']['child_name'] . '"');
        return $out;
    }

    function _list($opts = array()) {

        $has_paging = !isset($opts['no_paging']);
        $show_sortings = isset($opts['show_sortings']);
        $per_page = isset($opts['per_page']) ? $opts['per_page'] : 10;
        $per_page = min(100, max(1, (int) $per_page));

        $cond = new Conditions();
        if (isset($opts['historical']) && $opts['historical']) {
            $cond->setSorting(array('eventTime' => array('order' => 'desc', 'title' => 'по исторической дате')), array('eventTime' => array('order' => 'desc', 'title' => 'по исторической дате')));
        }else
            $cond->setSorting(array('createTime' => array('order' => 'desc', 'title' => 'по дате')), array('createTime' => array('order' => 'desc', 'title' => 'по дате')));
        $cond->setPaging(100000, $per_page);

        $where = array('1');
        if (isset($opts['where']))
            foreach ($opts['where'] as $w)
                $where[] = $w;
        $order = $cond->getSortingField() . ' ' . $cond->getSortingOrderSQL();
        $limit = $cond->getLimit();

        $query = 'SELECT SQL_CALC_FOUND_ROWS A.child_name as child_name,A.birthDate as birthDate,AE.*, LE.*,AE.description as description, LE.description as event_description, LE.title as event_title,AE.title as title, AE.creator_id as user_id,AE.id as id, LE.id as lib_event_id, LET.id as lib_template_id, AE.id as id
            FROM `album_events` AE
            LEFT JOIN `album` A ON A.id=AE.album_id
            LEFT JOIN `lib_events` LE ON LE.id=AE.event_id
            LEFT JOIN `lib_event_templates` LET ON LET.id=LE.template_id
WHERE (' . implode(' AND ', $where) . ')
ORDER BY ' . $order . ' LIMIT ' . $limit . '';
        $events = Database::sql2array($query, 'id');
        $uids = array();

        if (count($events))
            $field_values = Database::sql2array('SELECT AEF.*,LETF.title as event_field_title,T.* FROM `album_events_fields` AEF
                JOIN `lib_event_templates_fields` LETF ON LETF.field_id=AEF.field_id
                JOIN `lib_event_templates_fields_types` T ON T.id=LETF.type
                WHERE `event_id` IN(' . implode(',', array_keys($events)) . ')');
        else
            $field_values = array();

        foreach ($field_values as $values) {
            $events[$values['event_id']]['fields'][$values['field_id']] = $values;
        }


        foreach ($events as $event) {
            if ($event['user_id'])
                $uids[$event['user_id']] = $event['user_id'];
        }
        if (count($uids))
            $users = Users::getByIdsLoaded($uids);
        else
            $users = array();
        foreach ($events as &$event) {
            $event['user'] = isset($users[$event['user_id']]) ? $users[$event['user_id']]->data : array();
            $event['template_id'] = $event['template_id'] ? $event['template_id'] : 1;

            $image_id = $event['picture'];
            $event['pic_small'] = $image_id ? ImgStore::getUrl($image_id, Config::SIZES_PICTURE_SMALL) : false;
            $event['pic_normal'] = $image_id ? ImgStore::getUrl($image_id, Config::SIZES_PICTURE_NORMAL) : false;
            $event['pic_big'] = $image_id ? ImgStore::getUrl($image_id, Config::SIZES_PICTURE_BIG) : false;
            $event['pic_orig'] = $image_id ? ImgStore::getUrl($image_id, 0) : false;
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