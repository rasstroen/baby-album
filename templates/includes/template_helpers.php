<?php
/**
 *
 */
global $write;
global $data;
global $config;

function th_username($user) {
    $u = array();
    $u[] = $user['last_name'];
    $u[] = $user['middle_name'];
    $u[] = $user['first_name'];
    return implode(' ', $u);
}

function input_error($data, $field, $form) {
    if (isset($data['write']['error_' . $form][$field])) {
        ?>
        <em class="error"><?php echo $data['write']['error_' . $form][$field]; ?></em>
        <?php
    }
}

function input_val($data, $values, $field, $form, $return = false) {
    $res = '';
    if (isset($data['write']['value_' . $form][$field])) {
        $res = $data['write']['value_' . $form][$field];
    } else {
        $res = isset($values[$field]) ? $values[$field] : '';
    }

    if (!$return)
        echo htmlspecialchars($res);else
        return $res;
}

function th_process_block($block) {
    global $data;
    global $write;
    if (isset($data[$block])) {
        echo "\n<!--block " . $block . '-->' . "\n";
        foreach ($data[$block] as $module) {
            echo "\n<!--" . $module['module'] . '/' . $module['action'] . '/' . $module['mode'] . '-->' . "\n";
            $funcName = 'tp_' . $module['module'] . '_' . $module['action'] . '_' . $module['mode'];
            Log::timing('template [' . $funcName . ']');
            require_once (Config::need('templates_root') . '/modules/' . $module['module'] . '.php');
            if (function_exists($funcName)) {
                th_before_process_block($module['result'], $write);
                eval('echo ' . $funcName . '($module[\'result\']);');
                th_after_process_block($module['result']);
            }
            else
                echo ('missed function ' . $funcName . '($data) ');
            Log::timing('template [' . $funcName . ']');
        }
    }
}

function th_draw_top_menu() {
    $menu = array(
        'company' => 'Компания',
        'projects' => 'Работы',
        'news' => 'События',
        'contacts' => 'Контакты',
    );
    $folder = array_pop(Site::$request_uri_array);

    foreach ($menu as $name => $item) {
        if ($folder == $name) {
            ?>
            <li><?php echo $item; ?></li>
            <?php
        } else {
            ?>
            <li><a href="/<?php echo $name; ?>"><?php echo $item; ?></a></li>
            <?php
        }
    }
}

function th_draw_bottom() {
    ?>
    <div class="both bottomBlock">
        <div class="centered">
            <div class="both box<? if (isset($hidePhone)): ?> nocontacts<? endif ?><? if (isset($hideSocial)): ?> nosocial<? endif ?>">
                <div class="call">Позвоните нам +7 (495) <span>967 25 97</span> или напишите письмо <a href="mailto:ask@newidols.ru">ask@newidols.ru</a></div>
                <div class="links">
                    <a href="" class="fb"></a>
                    <a href="" class="tw"></a>
                </div>
                <div class="copyright">&copy; 2009—2012 <a href="/">NewIdols</a></div>
            </div>
        </div>
    </div>
    <?php
}

function th_draw_lang() {
    ?>
    <li class="lang">
        <p id="lang">Rus<span></span></p>
        <div>
            <span>Rus</span>
            <a href="">Eng</a>
        </div>
    </li>
    <?php
}

function th_before_process_block(&$data, $write) {
    $data['write'] = $write;
    if (isset($data['conditions'])) {
        foreach ($data['conditions'] as $conditions_block) {
            switch ($conditions_block['mode']) {

                case 'sorting':
                    th_conditions_sorting($conditions_block);
                    break;
            }
        }
    }
}

function th_after_process_block($data) {
    if (isset($data['conditions'])) {
        foreach ($data['conditions'] as $conditions_block) {
            switch ($conditions_block['mode']) {
                case 'paging':
                    th_conditions_paging($conditions_block);
                    break;
            }
        }
    }
}

function th_dump($data) {
    ?>
    <p>params for template:<pre><?php print_r($data); ?></pre></p>
    <?php
}

/**
 * paging
 */
function th_conditions_paging($data) {
    ?>
    <div class="conditions_paging">
        <?php
        foreach ($data['options'] as $page) {
            ?>
            <div class="page<?php
        echo isset($page['current']) && $page['current'] ? ' current ' : '';
        echo isset($page['last']) && $page['last'] ? ' last ' : '';
        echo isset($page['next']) && $page['next'] ? ' next ' : '';
        echo isset($page['first']) && $page['first'] ? ' first ' : '';
            ?>">
                <a href="<?php echo $page['path'] ?>"><?php echo $page['title']; ?></a>
            </div >
            <?php
        }
        ?>
    </div>
    <?php
}

/**
 * sorting
 */
function th_conditions_sorting($data) {
    ?>
    <div class="conditions_sorting">
        <?php
        foreach ($data['options'] as $sorting_option) {
            ?>
            <div class="sort <?php
        echo isset($sorting_option['current_order']) ? $sorting_option['current_order'] : 'desc';
            ?>">
                <a href="<?php echo $sorting_option['path'] ?>"><?php echo $sorting_option['title']; ?></a>
            </div >
            <?php
        }
        ?>
    </div>
    <?php
}

function th_prepare_price($price) {
    return '<em class="price">' . sprintf('%.2f', $price) . ' руб.</em>';
}

function th_form_field_error($field_name, $data) {

    if (isset($data[$field_name])) {
        echo '<em class="error">' . $data[$field_name] . '</em>';
    }
}