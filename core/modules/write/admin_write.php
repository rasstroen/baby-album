<?php

/**
 *
 * @author mchubar
 */
class admin_write extends write {

    function process() {
        switch ($_POST['action']) {
            case 'edit_event':
                $this->edit_event();
                break;
            case 'edit_template':
                $this->edit_template();
                break;
        }
    }

    function edit_template() {
        $id = (int) $_POST['id'];
        $q = array();
        foreach ($_POST['title'] as $field_id => $title) {
            $type = $_POST['type'][$field_id];
             $important = isset($_POST['important'][$field_id]) ? 1 : 0;
            $field_id = is_numeric($field_id) ? $field_id : 'NULL';
            $template_id = $id;
            $pos = 0;
            Database::query('REPLACE INTO `lib_event_templates_fields` SET
                `field_id`=' . $field_id . ',
                `template_id`=' . $template_id . ',
                `pos`=' . $pos . ',
                `type`=' . $type . ',
                `important`=' . $important . ',
                `title`=' . Database::escape($title) . '');
        }
    }

    function edit_event() {
        $id = $_POST['id'] ? $_POST['id'] : 'NULL';
        Database::query('INSERT INTO `lib_events` SET
            `id` = ' . $id . ',
            `title`=' . Database::escape($_POST['title']) . ',
            `male`=' . Database::escape($_POST['male']) . ',
            `age_start_days`=' . Database::escape($_POST['age_start_days']) . ',
            `age_end_days`=' . Database::escape($_POST['age_end_days']) . ',
            `description`=' . Database::escape($_POST['description']) . ',
            `need_photo`=' . Database::escape($_POST['need_photo']) . ',
            `need_description`=' . Database::escape($_POST['need_description']) . ',
            `template_id`=' . Database::escape($_POST['template_id']) . '
                ON DUPLICATE KEY UPDATE
            `title`=' . Database::escape($_POST['title']) . ',
            `male`=' . Database::escape($_POST['male']) . ',
            `age_start_days`=' . Database::escape($_POST['age_start_days']) . ',
            `age_end_days`=' . Database::escape($_POST['age_end_days']) . ',
            `description`=' . Database::escape($_POST['description']) . ',
            `need_photo`=' . Database::escape($_POST['need_photo']) . ',
            `need_description`=' . Database::escape($_POST['need_description']) . ',
            `template_id`=' . Database::escape($_POST['template_id']) . '
                ');
        $id = ($id == 'NULL') ? Database::lastInsertId() : $id;
        header('Location: /admin/event/' . $id . '/edit');
    }

}