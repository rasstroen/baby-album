<?php

/**
 *
 * @author mchubar
 */
class module_admin extends module {

    function _process($action, $mode) {
        if (!CurrentUser::$authorized) {
            header('Location: /');
            exit(0);
        }

        if (!Users::getByIdLoaded(CurrentUser::$id)->isAdmin()) {
            header('Location: /logout');
            exit(0);
        }

        switch ($action) {
            case 'list':
                switch ($mode) {
                    case 'events':
                        return $this->listEvents();
                        break;
                }
                break;
            case 'edit':
                switch ($mode) {
                    case 'event':
                        return $this->editEvent();
                        break;
                }
                break;
        }
    }

    function editEvent() {
        $event_id = array_keys(Site::$request_uri_array);
        $event_id = $event_id[2];
       
        $out = $this->listEvents(array('cond' => false, 'where' => array('id' => $event_id)));
        $out['lib_templates'] = Database::sql2array('SELECT * FROM `lib_event_templates` ORDER BY `title`','id');
        return $out;
    }

    function listEvents($opts = array()) {
        $cond = new Conditions();
        $per_page = 10;
        $cond->setPaging(10000, $per_page);

        $where = array(1);
        if (isset($opts['where']))
            foreach ($opts['where'] as $f => $v) {
                $where[] = $f . '=' . Database::escape($v);
            }

        $events = Database::sql2array('SELECT SQL_CALC_FOUND_ROWS * FROM `lib_events` WHERE ' . implode(' AND ', $where) . ' ORDER BY age_start_days,age_end_days  LIMIT ' . $cond->getLimit());
        $cond->setPaging(Database::sql2single('SELECT FOUND_ROWS()'), $per_page);
        $template_ids = array();
        foreach ($events as $event) {
            $template_ids[$event['template_id']] = 1;
        }

        if (count($template_ids)) {
            $templates = Database::sql2array('SELECT * FROM lib_event_templates T WHERE id IN(' . implode(',', array_keys($template_ids)) . ')', 'id');
            foreach ($events as &$event) {
                $event['template'] = isset($templates[$event['template_id']]) ? $templates[$event['template_id']]['title'] : false;
            }
        }

        $data['events'] = $events;
        if (!isset($opts['cond']) || $opts['cond'] == true)
            $data['conditions'] = $cond->getConditions();
        
        return $data;
    }

}