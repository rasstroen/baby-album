<?php

/**
 *
 * @author mchubar
 */
function draw_admin_menu() {
    ?>
    <ul>
        <li><a href="/admin/">Эвенты</a></li>
        <li><a href="/admin/templates">Шаблоны эвентов</a></li>
    </ul>
    <?php
}

function format_child_age_days_period($day1, $day2) {
    return $day1 . '-' . $day2 . ' дн';
}

function tp_admin_edit_event($data) {
    $event = isset($data['events']) ? array_pop($data['events']) : array();
    ?>
    <style>
        input{width:290px;}
    </style>
    <a href="/admin/">Назад к списку эвентов</a>
    <form method="post">
        <input type="hidden" value="admin" name="writemodule">
        <input type="hidden" value="edit_event" name="action">
        <input type="hidden" value="<?php input_val($data, $event, 'id', 'edit_event') ?>" name="id">

        <table width="100%">
            <tr>
                <td>Название<?php input_error($data, 'title', 'edit_event'); ?></td>
                <td width="300px"><input value="<?php input_val($data, $event, 'title', 'edit_event') ?>" name="title"></td>
                <td>отображается юзеру</td>
            </tr>
            <tr>
                <td>Пол<?php input_error($data, 'male', 'edit_event'); ?></td>
                <td><input value="<?php input_val($data, $event, 'male', 'edit_event') ?>" name="male"></td>
                <td>0-всё равно,1-только мальчики,2-только девочки могут заполнять эвент</td>
            </tr>
            <tr>
                <td>Возраст от<?php input_error($data, 'age_start_days', 'edit_event'); ?></td>
                <td><input value="<?php input_val($data, $event, 'age_start_days', 'edit_event') ?>" name="age_start_days"></td>
                <td>В днях! Примерное время начала эвента, например, 93 день - первые зубы</td>
            </tr>
            <tr>
                <td>Возраст до<?php input_error($data, 'age_end_days', 'edit_event'); ?></td>
                <td><input value="<?php input_val($data, $event, 'age_end_days', 'edit_event') ?>" name="age_end_days"></td>
                <td>В днях! Примерное время окончания эвента, например, 365 день - первые зубы.</td>
            </tr>
            <tr>
                <td colspan="3">
                    Например, я мама ребенка, которому 63 дня. Мне напоминается об эвентах, которые подходят мне по возрасту - все эвенты
                    начинающиеся с дней до 63 ого и оканчивающиеся после 63его
                </td>
            </tr>
            <tr>
                <td>Описание<?php input_error($data, 'description', 'edit_event'); ?></td>
                <td><textarea name="description"><?php input_val($data, $event, 'description', 'edit_event') ?></textarea></td>
                <td>Текст, который показывается при заполнении эвента, и в описании эвента</td>
            </tr>
            <tr>
                <td>Необходимо фото?<?php input_error($data, 'need_photo', 'edit_event'); ?></td>
                <td><input value="<?php input_val($data, $event, 'need_photo', 'edit_event') ?>" name="need_photo"></td>
                <td>1 или 0. Не даем сохранить эвент, пока не загружена фотка. Например, эвент "моя первая фотография" без фото - это фигня а не эвент.</td>
            </tr>
            <tr>
                <td>Необходимо описание?<?php input_error($data, 'need_description', 'edit_event'); ?></td>
                <td><input value="<?php input_val($data, $event, 'need_description', 'edit_event') ?>" name="need_description"></td>
                <td>1 или 0. Не даем сохранить эвент, пока не нет описания. Например, эвент "текст моей первой песенки" без текста - это фигня а не эвент.</td>
            </tr>
            <tr>
                <td>Шаблон</td>
                <td><select name="template_id">
                        <option value="0">без шаблона</option>
                        <?php
                        foreach ($data['lib_templates'] as $id => $template) {
                            $selected = ($id == input_val($data, $event, 'template_id', 'edit_event', $return = true)) ? 'selected="selected"' : '';
                            ?><option <?php echo $selected; ?> value="<?php echo $id; ?>"><?php echo $template['title']; ?></option><?php
                }
                        ?>
                    </select>
                </td>
                <td>Шаблон эвента</td>
            </tr>
            <tr>
                <td colspan="3">
                    <input name="save" type="submit" value="сохранить">
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    Шаблон эвента - это список дополнительных полей, который нужно заносить мамам при заполнении эвента.<br/>
                    Обычно эвент состоит из:
                    <ul>
                        <li>-Фотография</li>
                        <li>-Заголовок</li>
                        <li>-Дата</li>
                    </ul>
                    Шаблоны содержат список полей с флагами "обязательное к заполнению" или "не обязательное к заполнению" и определенным типом. Например, шаблон
                    "Рождение" включает обязательные поля:Мой вес(типа Вес), Мой рост(типа Рост), цвет глазок(типа Цвет глаз). Так мамы точно не забудут описать эти поля.
                    Можно выбрать шаблон из <a href="/admin/templates/">списка</a> или сначала <a href="/admin/templates/0/edit/">добавить новый шаблон</a>.
                </td>
            </tr>
        </table>

    </form>
    <?php
}

function tp_admin_list_events($data) {
    draw_admin_menu();
    ?><h2>Эвенты</h2>
    <style>
        .admin_events th,.admin_events td{text-align: center;padding:2px; vertical-align: middle}
    </style>
    <table border="1" cellpadding="0" cellspacing="1" class="admin_events">
        <tr>
            <th>id</th>
            <th>название</th>
            <th>пол</th>
            <th>возраст</th>
            <th>описание</th>
            <th>фото необходимо</th>
            <th>описание необходимо</th>
            <th>шаблон эвента</th>
            <th>управление</th>
        </tr>
        <?php foreach ($data['events'] as $event) {
            ?>
            <tr>
                <td><?php echo $event['id']; ?></td>
                <td><?php echo $event['title']; ?></td>
                <td><?php echo ($event['male'] == 0) ? 'не важен' : ($event['male'] == 1 ? 'мальчик' : 'девочка'); ?></td>
                <td><?php echo format_child_age_days_period($event['age_start_days'], $event['age_end_days']); ?></td>
                <td><?php echo $event['description']; ?></td>
                <td><?php echo $event['need_photo'] ? 'да' : 'нет'; ?></td>
                <td><?php echo $event['need_description'] ? 'да' : 'нет'; ?></td>
                <td><a href="/admin/templates/<?php echo $event['template_id']; ?>"><?php echo $event['template']; ?></a></td>
                <td><a href="/admin/event/<?php echo $event['id']; ?>/edit">редактировать</a></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <a href="/admin/event/0/edit">Добавить эвент</a>
    <?php
}