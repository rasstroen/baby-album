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

                        <img src="<?php echo $user->getAvatar(true) ?>" />
                        <img src="<?php echo $user->getAvatar(false) ?>" />

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

function tp_user_show_profile_small($data) {
    $udata = $data['data'];
    $user = $data['user'];
    if (CurrentUser::$id == $user->id)
        $self = true;
    else
        $self = false;
    ?>
    <div class="show_profile_small">
        <div class="block credentials">

            <img src="<?php echo $user->getAvatar(false) ?>" />

        </div>
        <div class="block">
            <ul>
                <li><a href="/u/<?php echo $user->id; ?>/albums">Альбомы</a></li>
                <li><a href="/u/<?php echo $user->id; ?>/comments">Комментарии</a></li>
                <li><a href="/u/<?php echo $user->id; ?>/best">Лучшие фотографии</a></li>
                <li><a href="/u/<?php echo $user->id; ?>/badges">Награды</a></li>
            </ul>
        </div>
    </div>
    <?php
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
            <h2><?php echo $user->data['nickname'] ?></h2>
            <?php if ($self) { ?><div class="edit"><a href="/u/<?php echo $user->id; ?>/edit">редактировать</a></div><?php } ?>
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
    $values = array();
    ?>
    <div class="plank_login">
        <form method="post">
            <input type="hidden" value="user" name="writemodule">
            <input type="hidden" value="auth" name="action">
            <span>E-mail: </span><?php input_error($data, 'email', 'auth'); ?>
            <input class="email" type="text" name="email" value="<?php input_val($data, $values, 'email', 'auth') ?>" />
            <span>Пароль: </span><?php input_error($data, 'password', 'auth'); ?>
            <input class="pwd" type="password" name="password" />
            <input class="submit" type="submit" value="войти">
            <a href="/register">зарегистрироваться</a>
        </form>
    </div>
    <?php
}

function draw_user_logged_plank($data) {
    $user = Users::getByIdLoaded(CurrentUser::$id);
    ?>
    <div class="plank_logged">
        <span>Вы вошли как</span><a class="name" href="/u/<?php echo $user->data['id'] ?>"><?php echo th_username($user->data) ?></a><a class="logout" href="/logout">выйти</a>
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
    <div class="register_form">
        <form method="post">
            <input type="hidden" value="user" name="writemodule">
            <input type="hidden" value="register" name="action">
            <div><span><a title="Адрес E-mail, для подтверждения регистрации.">E-mail&nbsp;*:</a></span>
                <input class="email" type="text" name="email" />
                <?php input_error($data, 'email', 'register'); ?>
            </div>
            <div><span><a title="Любой пароль для входа на сайт">Пароль&nbsp;*:</a></span>
                <input class="password" type="password" name="password" />
                <?php input_error($data, 'password', 'register'); ?>
            </div>
            <div><span><a title="Ник, под которым Вас будут видеть пользователи сайта">Никнейм:</a></span>
                <input class="nickname" name="nickname" />
                <?php input_error($data, 'nickname', 'register'); ?>
            </div>
            <div>
                <input class="submit" type="submit" value="зарегистрироваться">
            </div>
        </form>
    </div>
    <?php
}