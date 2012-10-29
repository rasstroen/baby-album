<?php

/**
 *
 * @author mchubar
 */
function tp_user_edit_profile($data) {
    $user = Users::getByIdLoaded(CurrentUser::$id);
    $values = $user->data;
    ?>
    <div class="user_edit">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="user" name="writemodule" />
            <input type="hidden" value="edit" name="action" />
            <input type="hidden" value="<?php echo $values['id']; ?>" name="id" />
            <div class="block">
                <div class="head">Расскажите о себе</div>
                <div class="data">
                    <div class="title">Ник<?php input_error($data, 'nickname', 'edit'); ?></div>
                    <div class="value">
                        <input name="nickname" value="<?php input_val($data, $values, 'nickname', 'edit') ?>">
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="head">Фотография профиля</div>
                <div class="data">
                    <div class="title">в формате JPG,PNG</div>
                    <div class="value">
                        <input name="userpic" type="file">
                        <?php if ($values['pic_small']) { ?>
                            <img src="<?php echo $user->getAvatar(true) ?>" />
                            <img src="<?php echo $user->getAvatar(false) ?>" />
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="block"><input type="submit" class="submit" value="сохранить" /></div>
        </form>
    </div>
    <?php
}

function _th_draw_albums_profile_list($albums) {
    ?><div class="profile_album_list"><?php
    foreach ($albums as $album) {
        ?><div><a href="/album/<?php echo $album['id']; ?>"><?php echo $album['child_name']; ?></a></div><?php
    }
    ?></div><?php
}

function tp_user_show_profile($data) {
    $udata = $data['data'];
    $user = $data['user'];
    if (CurrentUser::$id == $user->id)
        $self = true;
    else
        $self = false;
    ?>
    <div class="show_profile">
        <div class="block credentials">
            <?php if ($self) { ?><div class="edit"><a href="/u/<?php echo $user->id; ?>/edit">редактировать</a></div><?php } ?>
            <div class="field"><h3><?php echo $udata['nickname'] ?></h3></div>
            <?php if ($udata['avatar_small']) { ?>
                <img src="<?php echo $user->getAvatar(false) ?>" />
            <?php } ?>
        </div>
        <div class="block albums">
            <h3>Альбомы</h3>
            <?php
            if (count($data['albums'])) {
                _th_draw_albums_profile_list($data['albums']);
            } else {
                ?>
                <div class="notify">Пока нет ни одного альбома</div>
                <?php
            }
            ?>
            <?php if ($self) {
                ?><div class="add"><a href="/album/0/edit">Добавить альбом</a></div><?php }
            ?>
        </div>
    </div>
    <?php
}

function tp_user_show_top_menu($data) {
    ?><ul class="menu"><?
    global $uri;

    $current = isset($uri[0]) ? $uri[0] : false;

    $menu = array(
        '' => 'Главная',
        'publication' => 'Публикации',
        'albums' => 'Альбомы',
            //'blog' => 'Блоги',
            //'news' => 'Новости',
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
    $user = Users::getByIdLoaded(CurrentUser::$id);
    ?>
    <div class="plank_logged">
        <div class="card">
            <div class="avatar">
                <img src="<?php echo $user->getAvatar(); ?>"/>
            </div>
            <a href="/u/<?php echo $user->data['id'] ?>"><?php echo th_username($user->data) ?></a>
        </div>
        <a href="/logout">выйти</a>
    </div>
    <?php
}

function tp_user_show_static_auth($data) {
    if (CurrentUser::$authorized) {
        draw_user_logged_plank($data);
    } else {
        draw_user_auth_plank($data);
    }
}

function tp_user_show_confirmation($data) {
    if ($data['success']) {
        ?><h2>Поздравляем! Вы подтвердили свой почтовый адрес.</h2><?php
    } else {
        ?><h2>Почтовый адрес уже подтвержден, или неверный код подтверждения.</h2><?php
    }
}

function tp_user_show_register($data) {
    if (isset($data['write']['success'])) {
        ?>
        <div class="register_success">
            Вы успешно зарегистрированы на сайте. На Ваш Email отправлено письмо для подтверждения почтового ящика. После подтверждения Вы сможете пользоваться всеми функциями сайта.
        </div>
        <?php
        return;
    }
    ?>
    <form method="post">
        <input type="hidden" value="user" name="writemodule">
        <input type="hidden" value="register" name="action">
        e-mail:<?php input_error($data, 'email', 'register'); ?>
        <input type="text" name="email" />
        пароль:<?php input_error($data, 'password', 'register'); ?>
        <input type="password" name="password" />
        никнейм:<?php input_error($data, 'nickname', 'register'); ?>
        <input name="nickname" />
        <input type="submit" value="зарегистрироваться">
    </form>
    <?php
}