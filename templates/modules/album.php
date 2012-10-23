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
                    ?><input name="<?php echo $field['type'] ?>" value="<?php echo date('Y-m-d H:i',strtotime($field_value)) ?>">
                    <script>
                        $('input[name="<?php echo $field['type'] ?>"]').datetimepicker({
                            dateFormat:"yy-mm-dd",
                            timeFormat: 'hh:mm'
                        });
                    </script>
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

function tp_album_show_event($data) {
    $event = $data['event'];
    ?>
    <div class="event_one">
        <div class="head"></div>
        <div class="body">
            <div class="img">
                <a href="<?php echo $event['pic_big']; ?>">
                    <img src="<?php echo $event['pic_normal']; ?>">
                </a>
                <a href="<?php echo $event['pic_orig']; ?>">
                    скачать оригинал
                </a>
            </div>
        </div>
        <div class="foot"></div>
    </div>
    <?php
}

function tp_album_edit_event($data) {
    $event = $data['event'];
    $album_id = (int) $event['album_id'];
    $values = $event;
    $fields = array();
    $title = '';
    $title = isset($event['event_title']) ? $event['event_title'] : $title;
    $title = isset($event['title']) ? $event['title'] : $title;
    if (!$title)
        $title = 'Редактирование события';
    else
        $title = 'Событие "' . $title . '"';
    if ($event['template_id'])
        $fields = getTemplateFields((int) $event['template_id']);
    ?><div class="event_edit">
        <h3><?php echo $title; ?></h3>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="album" name="writemodule">
            <input type="hidden" value="edit_event" name="action">
            <input type="hidden" value="<?php input_val($data, $values, 'id', 'edit_event') ?>" name="id">
            <input type="hidden" value="<?php echo $album_id; ?>" name="album_id">
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
        $self = (CurrentUser::$id == $event['user_id']);
            ?>
    <div class="event_one">
        <?php if ($self) {
            ?><div class="edit"><a href="/album/<?php echo $event['album_id']; ?>/event/<?php echo $event['id']; ?>/edit">редактировать</a></div><?php } ?>
        <div class="head">

        </div>
        <div class="body">
            <div class="img">
                <a href="<?php echo $event['pic_normal']; ?>">
                    <img src="<?php echo $event['pic_small']; ?>">
                </a>
                <a href="<?php echo $event['pic_big']; ?>">
                    скачать в большом размере
                </a>
                <a href="<?php echo $event['pic_orig']; ?>">
                    скачать оригинал
                </a>
            </div>
        </div>
        <div class="foot"></div>
    </div>
    <?php
}

function _th_show_my_baby_age($data) {
    $years = $data['age_days']->y;
    $months = $data['age_days']->m;
    $days = $data['age_days']->d;
    $q = array();
    if ($years)
        $q[] = $years . ' ' . declOfNum($years, array('год', 'года', 'лет'));
    if ($months)
        $q[] = $months . ' ' . declOfNum($months, array('месяц', 'месяца', 'месяцев'));
    if ($months && $days)
        $q[] = 'и';
    if ($days)
        $q[] = $days . ' ' . declOfNum($days, array('день', 'дня', 'дней'));
    ?>
    <div class="my_age">
        <?php if ($data['album']['pic_small']) { ?>
            <div class="photo">
                <img src="<?php echo $data['album']['pic_small']; ?>" />
            </div>
        <?php } ?>
        Мне уже <?php echo implode(' ', $q); ?>
    </div>
    <?php
}

function _th_show_suggest($data) {
    ?> <div class="suggest">
        <h3>Возможно, вы забыли поделиться событием?</h3><?php
    foreach ($data['suggest'] as $suggest) {
        ?><div class="event">
                <a href="/album/<?php echo $data['album']['id'] ?>/event/new/<?php echo $suggest['id']; ?>">"<?php echo $suggest['title']; ?>"</a>
            </div><?php
    }
    ?></div><?php
}

function tp_album_show_suggest_event($data) {
    if (isset($data['age_days'])) {
        _th_show_my_baby_age($data);
    }
    if (isset($data['suggest'])) {
        _th_show_suggest($data);
    }
}

function tp_album_edit_item($data) {
    $values = $data['album'];
    ?><div class="album_edit">
        <h2>Изменение настроек альбома</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="album" name="writemodule">
            <input type="hidden" value="edit_album" name="action">
            <input type="hidden" value="<?php echo $values['id']; ?>" name="album_id">
            <div class="head">Мой ребёнок</div>
            <div class="data">
                <div class="title">Имя <?php input_error($data, 'child_name', 'edit'); ?></div>
                <div class="value">
                    <input name="child_name" value="<?php input_val($data, $values, 'child_name', 'edit') ?>">
                </div>
            </div>
            <div class="data">
                <div class="title">Дата рождения <?php input_error($data, 'birthDate', 'edit'); ?></div>
                <div class="value">
                    <input name="birthDate" value="<?php input_val($data, $values, 'birthDate', 'edit') ?>">
                </div>
            </div>
            <div class="data">
                <div class="title">Обложка альбома</div>
                <div class="value">
                    <input name="cover" type="file">
                    <?php if ($values['pic_small']) { ?>
                        <img src="<?php echo $values['pic_normal']; ?>" />
                    <?php } ?>
                </div>
            </div>
            <div class="submit">
                <input type="submit" value="сохранить">
            </div>
        </form>
    </div>

    <?php
}

function tp_album_list_events($data) {
    ?>
    <div id="album" class="album">
        <?php
        $self = (CurrentUser::$id == $data['album']['user_id']);
        if ($self) {
            ?>
            <div class="owner">
                <span><a href="/album/<?php echo $data['album']['id'] . '/edit' ?>">Настройки альбома</a></span>
                <span><a href="/album/<?php echo $data['album']['id'] . '/event/0/edit' ?>">Добавить событие</a></span>
            </div>
            <?php
        }
        ?><div class="album">
            <?php
            foreach ($data['events'] as $event) {
                _th_draw_event_in_list($event);
            }
            ?>
        </div>
    </div><?php
    }