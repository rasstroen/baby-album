<?php 
/**
 *
 * @author mchubar
 */
function tp_user_show_top_menu($data) {
    ?><ul class="menu"><?
    global $uri;

    $current = isset($uri[0]) ? $uri[0] : false;

    $menu = array(
        '' => 'Главная',
        'publication' => 'Публикации',
        'album' => 'Альбомы',
        'blog' => 'Блоги',
        'news' => 'Новости',
    );

    foreach ($menu as $key => $title) {
        if ($key == $current) {
            ?><li class="current"><a href="/<?php echo $key ?>"><?php echo $title; ?></a></li><?php
        } else {
            ?><li><a href="/<?php echo $key ?>"><?php echo $title; ?></a></li><?php
        }
    }
    ?></ul><?
}

function draw_user_auth_plank($data) {
    ?>
    <form method="post">
        <input type="hidden" value="user" name="writemodule">
        <input type="hidden" value="auth" name="action">
        e-mail:<?php input_error($data, 'email', 'auth'); ?>
        <input type="text" name="email" />
        пароль:<?php input_error($data, 'password', 'auth'); ?>
        <input type="password" name="password" />
        <input type="submit" value="войти">
        <a href="/register">зарегистрироваться</a>
    </form>
    <?php
}

function draw_user_logged_plank($data) {
    
}

function tp_user_show_static_auth($data) {
    if (CurrentUser::$authorized) {
        draw_user_logged_plank($data);
    } else {
        draw_user_auth_plank($data);
    }
}

function tp_user_show_register($data) {
    ?>
    <form method="post">
        <input type="hidden" value="user" name="writemodule">
        <input type="hidden" value="register" name="action">
        e-mail:<?php input_error($data, 'email', 'register'); ?>
        <input type="text" name="email" />
        пароль:<?php input_error($data, 'password', 'register'); ?>
        <input type="password" name="password" />
        <input type="submit" value="зарегистрироваться">
    </form>
    <?php
}