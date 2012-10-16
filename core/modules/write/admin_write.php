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