<?php

function getTemplateFields($template_id) {
    $query = 'SELECT * FROM `lib_event_templates` LET
        LEFT JOIN `lib_event_templates_fields` LETF ON LET.id=LETF.template_id
        LEFT JOIN `lib_event_templates_fields_types` LETFT ON LETFT.id=LETF.`type`
        WHERE LET.id=' . $template_id . ' ORDER BY pos';
    $data = Database::sql2array($query);

    foreach ($data as $field) {
        $out[$field['type_name']] = array(
            'type' => $field['type_name'],
            'important' => $field['important'],
            'title' => $field['title'],
            'field_id' => $field['field_id'],
            'pos' => $field['pos']
        );
    }
    return $out;
}


function _th_draw_editing_field($field, $data) {

    $field_value = '';
    if (isset($data['event']['fields'][$field['field_id']])) {
        $field_value = $data['event']['fields'][$field['field_id']]['value_int'];
        if (!$field_value)
            $field_value = $data['event']['fields'][$field['field_id']]['value_varchar'];
        if (!$field_value)
            $field_value = $data['event']['fields'][$field['field_id']]['value_text'];
    }
    if (isset($data['write']['value'][$field['type']]))
        $field_value = $data['write']['value'][$field['type']];
    ?>
    <div class="field <?php echo $field['type'] ?>">
        <div class="title">
            <?php echo $field['title']; ?>
            <?php if ($field['important']) {
                ?><span class="im">*</span><?php
    }
            ?>
        </div>
        <div class="value"><?php
        echo '<!-- field of ' . $field['type'] . ' type-->';
        switch ($field['type']) {
            case 'eventTitle':
            case 'name':
            case 'height':
            case 'weight':
                    ?><input name="<?php echo $field['type'] ?>" value="<?php echo $field_value ?>">
                    <?php
                    input_error($data, $field['type'], '');
                    break;
                case 'eyecolor':
                    ?>
                    <select name="<?php echo $field['type'] ?>">
                        <option value="0" >---</option><?php
            foreach (Config::$eyecolors as $id => $title) {
                $selected = ($id == $field_value) ? 'selected="selected" ' : '';
                        ?>
                            <option value="<?php echo $id ?>" <?php echo $selected; ?>><?php echo $title; ?></option>
                        <?php } ?>
                    </select>
                    <?php
                    break;
                case 'eventTime':
                    ?><input name="<?php echo $field['type'] ?>" value="<?php echo $field_value ?>">
                    <?php
                    input_error($data, $field['type'], '');
                    break;
                case 'description':
                    ?><textarea name="<?php echo $field['type'] ?>"><?php echo $field_value ?></textarea>
                    <?php
                    input_error($data, $field['type'], '');
                    break;
                case 'photo':
                    ?><input type="file" name="<?php echo $field['type'] ?>">
                    <?php
                    input_error($data, $field['type'], '');
                    break;
                default:
                    print_r($field);
                    break;
            }
            ?>
        </div>
    </div>
    <?php
}

function tp_album_show_event($data){
    dpr($data);
}

function tp_album_edit_event($data) {
    $event = $data['event'];
    $values = $event;
    $fields = array();
    if ($event['template_id'])
        $fields = getTemplateFields((int) $event['template_id']);
    ?><div class="event_edit">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="album" name="writemodule">
            <input type="hidden" value="edit_event" name="action">
            <input type="hidden" value="<?php input_val($data, $values, 'id', 'edit_event') ?>" name="id">
            <?php
            foreach ($fields as $field) {
                _th_draw_editing_field($field, $data);
            }
            ?>
            <div class="submit">
                <input type="submit" value="сохранить">
            </div>
        </form>
    </div><?php
    }

    function _th_draw_event_in_list($event) {
        $self = CurrentUser::$id = $event['user_id'];
            ?>
    <div class="event">
        <?php if ($self) {
            ?><div class="edit"><a href="/album/<?php echo $event['album_id']; ?>/event/<?php echo $event['id']; ?>/edit">редактировать</a></div><?php } ?>
    </div>
    <?php
}

function tp_album_list_events($data) {
    ?><div class="album">
        <?php
        foreach ($data['events'] as $event) {
            _th_draw_event_in_list($event);
        }
        ?>
    </div><?php
}