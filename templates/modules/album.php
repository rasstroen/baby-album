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
                    ?><input name="<?php echo $field['type'] ?>" value="<?php echo date('Y-m-d H:i', strtotime($field_value)) ?>">
                    <script>
                        $('input[name="<?php echo $field['type'] ?>"]').datetimepicker({
                            dateFormat:"yy-mm-dd",
                            timeFormat: 'hh:mm',
                            timeText: 'Время',
                            hourText: 'Часы',
                            minuteText: 'Минуты',
                            secondText: 'Секунды',
                            currentText: 'Сегодня',
                            closeText: 'Закрыть'
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

function _th_draw_event_field($field) {
    switch ($field['type_name']) {
        case 'eventTime':case'photo':case'description':case'eventTitle':
            break;
        case 'name':// имя ребёнка
            if ($field['value_varchar']) {
                echo "\n";
                ?><div class="ft"><?php echo $field['event_field_title']; ?></div><div class="add t_<?php echo $field['type_name']; ?>"><?php echo $field['value_varchar'] ?></div><?php
            }
            break;
        case 'weight':// вес
            if ($field['value_int']) {
                echo "\n";
                ?><div class="ft"><?php echo $field['event_field_title']; ?></div><div class="add t_<?php echo $field['type_name']; ?>"><?php echo $field['value_int'] ?></div><?php
            }
            break;
        case 'height':// рост
            if ($field['value_int']) {
                echo "\n";
                ?><div class="ft"><?php echo $field['event_field_title']; ?></div><div class="add t_<?php echo $field['type_name']; ?>"><?php echo $field['value_int'] ?></div><?php
            }
            break;
        case 'eyecolor':// рост
            if ($field['value_int']) {
                echo "\n";
                ?><div class="ft"><?php echo $field['event_field_title']; ?></div><div class="add t_<?php echo $field['type_name']; ?>"><?php echo Config::$eyecolors[$field['value_int']] ?></div><?php
            }
            break;
        default:
            dpr($field);
            break;
    }
}

function tp_album_show_event($data) {
    $event = $data['event'];
    $self = (CurrentUser::$id == $event['user_id']);
    ?>
    <div class="event_one event_type_<?php echo $event['event_id']; ?>">
        <div class="head">
            <div class="title"><?php echo $event['title']; ?></div>
            <div class="time"><?php echo $event['eventTime']; ?></div>
            <?php if ($self) {
                ?><div class="edit"><a href="/album/<?php echo $event['album_id']; ?>/event/<?php echo $event['id']; ?>/edit">редактировать</a></div><?php } ?>
        </div>
        <div class="body">
            <?php if ($event['pic_orig']) { ?>
                <div class="img">
                    <a href="<?php echo $event['pic_big']; ?>">
                        <img src="<?php echo $event['pic_normal']; ?>">
                    </a>
                    <a class="orig" href="<?php echo $event['pic_orig']; ?>">оригинал</a>
                </div>
            <?php } ?>
            <?php if ($event['description']) { ?>
                <div class="description"><?php echo $event['description']; ?></div>
            <?php } ?>
            <div class="additional">
                <?php
                foreach ($event['fields'] as $field)
                    _th_draw_event_field($field)
                    ?>
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
            <input type="hidden" value="<?php echo $event['event_id']; ?>" name="event_id">
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
        $user = Users::getByIdLoaded($event['user_id']);
            ?>
    <div class="event_one event_type_<?php echo $event['event_id']; ?>">
        <div class="head">
            <div class="user">
                <div class="av">
                    <img src="<?php echo $user->getAvatar(true); ?>">
                </div>
                <div class="lnk">из альбома <a href="/album/<?php echo $event['album_id']; ?>"><?php echo $event['child_name']; ?></a></div>
            </div>
            <div class="title"><?php echo $event['title']; ?></div>
            <div class="time"><?php echo $event['eventTime']; ?></div>
            <?php if ($self) {
                ?><div class="edit"><a href="/album/<?php echo $event['album_id']; ?>/event/<?php echo $event['id']; ?>/edit">редактировать</a></div><?php } ?>
        </div>
        <div class="body">
            <?php if ($event['pic_orig']) { ?>
                <div class="img">
                    <a href="<?php echo $event['pic_big']; ?>">
                        <img src="<?php echo $event['pic_small']; ?>">
                    </a>
                    <a class="orig" href="<?php echo $event['pic_orig']; ?>">оригинал</a>
                </div>
            <?php } ?>
            <?php if ($event['description']) { ?>
                <div class="description"><?php echo $event['description']; ?></div>
            <?php } ?>
            <div class="additional">
                <?php
                foreach ($event['fields'] as $field)
                    _th_draw_event_field($field)
                    ?>
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

function tp_album_list_suggested_events($data) {
    ?><div class="suggested_events">
    <?php
    $my_days = $data['age_days']->days;
    foreach ($data['suggest'] as $suggest) {
        list($from, $titles_) = getAgeInHumanReadableByDaysCount($suggest['age_start_days']);
        list($to, $titles) = getAgeInHumanReadableByDaysCount($suggest['age_end_days']);
        if ($titles_[0] != $titles[0])
            $from.=' ' . declOfNum($to, $titles_);
        if ($suggest['age_start_days'])
            $age_string = 'от ' . $from . ' до ' . $to . ' ' . declOfNum($to, $titles);
        else
            $age_string = 'до ' . $to . ' ' . declOfNum($to, $titles);
        $css = (($my_days > $suggest['age_start_days']) && ($my_days < $suggest['age_end_days'])) ? 'fit' : '';
        if (!$css)
            $css = (($my_days < $suggest['age_start_days'])) ? 'too_young' : 'too_old';

        $exists = isset($data['exists'][$suggest['id']]) ? $data['exists'][$suggest['id']]['id'] : false;
        if ($exists)
            $css.=' exists';
        ?><div class="event <?php echo 'e_' . $css; ?>">
            <?php if (!$exists) {
                ?><a href="/album/<?php echo $data['album']['id'] ?>/event/new/<?php echo $suggest['id']; ?>">"<?php echo $suggest['title']; ?>"</a><?php
        } else {
                ?><a href="/album/<?php echo $data['album']['id'] ?>/event/<?php echo $exists; ?>/edit">"<?php echo $suggest['title']; ?>"</a><?php
        }
            ?>

                <p class="description"><?php echo $suggest['description']; ?></p>
                <p class="age <?php echo $css; ?>"><?php echo $age_string; ?></p>
            </div><?php
    }
        ?>
    </div><?php
}

function _th_show_suggest($data) {
        ?> <div class="suggest">
        <h3>Возможно, вы забыли поделиться событием?</h3><?php
    foreach ($data['suggest'] as $suggest) {
            ?><div class="event">
                <a href="/album/<?php echo $data['album']['id'] ?>/event/new/<?php echo $suggest['id']; ?>">"<?php echo $suggest['title']; ?>"</a>
            </div><?php
    }
        ?>
        <div class="more">
            <a href="/album/<?php echo $data['album']['id'] ?>/suggested_events">все события</a>
        </div>
    </div><?php
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
                <div class="title">Пол <?php input_error($data, 'sex', 'edit'); ?></div>
                <div class="value">
                    <label for="radio_m">мальчик</label>
                    <input id="radio_m" type="radio" <?php if (input_val($data, $values, 'sex', 'edit', 1) == 1) echo 'checked="checked"'; ?> name="sex" value="1">
                    <label for="radio_d">девочка</label>
                    <input id="radio_d" type="radio" <?php if (input_val($data, $values, 'sex', 'edit', 1) == 2) echo 'checked="checked"'; ?> name="sex" value="2">
                </div>
            </div>
            <div class="data">
                <div class="title">Дата рождения <?php input_error($data, 'birthDate', 'edit'); ?></div>
                <div class="value">
                    <input name="birthDate" value="<?php input_val($data, $values, 'birthDate', 'edit') ?>">
                </div>
                <script>
                    $('input[name="birthDate"]').datepicker({
                        dateFormat:"yy-mm-dd",
                        timeFormat: 'hh:mm',
                        timeText: 'Время',
                        hourText: 'Часы',
                        minuteText: 'Минуты',
                        secondText: 'Секунды',
                        currentText: 'Теперь',
                        closeText: 'Закрыть'
                    });
                </script>
            </div>
            <div class="data">
                <div class="title">Фото малыша на обложку</div>
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

function tp_album_list_main($data) {
    ?>
    <div id="main_album" class="album">
        <div class="album">
            <?php
            foreach ($data['events'] as $event) {
                _th_draw_event_in_list($event);
            }
            ?>
        </div>
    </div><?php
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