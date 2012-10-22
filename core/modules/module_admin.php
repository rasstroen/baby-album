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
                    case 'templates':
                        return $this->listTemplates();
                        break;
                }
                break;
            case 'edit':
                switch ($mode) {
                    case 'event':
                        return $this->editEvent();
                        break;
                    case 'template':
                        return $this->editTemplate();
                        break;
                }
                break;
        }
    }

    function editEvent() {
        $event_id = array_values(Site::$request_uri_array);
        $event_id = $event_id[2];

        $out = $this->listEvents(array('cond' => false, 'where' => array('id' => $event_id)));
        $out['lib_templates'] = Database::sql2array('SELECT * FROM `lib_event_templates` ORDER BY `title`', 'id');
        return $out;
    }

    function editTemplate() {
        $template_id = array_values(Site::$request_uri_array);
        $template_id = $template_id[2];

        $template = $this->listTemplates(array('cond' => false, 'where' => array('id' => $template_id)));
        $template = $template['templates'][$template_id];


        $query = 'SELECT * FROM `lib_event_templates_fields` LETF
            LEFT JOIN  `lib_event_templates_fields_types` LETFT ON LETFT.id=LETF.type
            WHERE `template_id`=' . $template_id.' ORDER BY pos';
        $template['fields'] = Database::sql2array($query, 'field_id');
        $out['template'] = $template;
        return $out;
    }

    function listTemplates($opts = array()) {
        $cond = new Conditions();
        $per_page = 100;
        $cond->setPaging(10000, $per_page);

        $where = array(1);
        if (isset($opts['where']))
            foreach ($opts['where'] as $f => $v) {
                $where[] = $f . '=' . Database::escape($v);
            }

        $events = Database::sql2array('SELECT SQL_CALC_FOUND_ROWS * FROM `lib_event_templates` WHERE ' . implode(' AND ', $where) . ' ORDER BY id  LIMIT ' . $cond->getLimit(), 'id');
        $cond->setPaging(Database::sql2single('SELECT FOUND_ROWS()'), $per_page);

        $data['templates'] = $events;
        if (!isset($opts['cond']) || $opts['cond'] == true)
            $data['conditions'] = $cond->getConditions();

        return $data;
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