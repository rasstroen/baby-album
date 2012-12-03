<?php

/**
 *
 * @author mchubar
 */
function tp_user_show_connect_vk($data) {
    ?>
    <div class="user_connect_vk">
        <?php
        if ($data && $data['name']) {
            ?>
            <h3>Вы успешно привязали этот аккаунт к своему профилю:</h3>
            <div class="pic">
                <img src="<?php echo $data['pic']; ?>">
            </div>
            <div class="name">
                <?php echo $data['name']; ?>
            </div>
            <?php
        } else {
            ?>
            <span class="error">
                <?php echo $data['error']; ?>
            </span>
            <?php
        }
        ?>
    </div>
    <?php
}

function tp_user_show_pass_restore($data) {

    if (isset($data['write']['success'])) {
        ?>
        <h1>Пароль успешно заменён</h1>
        <?php
        return;
    }
    if (!$data['success']) {
        ?>
        <h1>Ссылка устарела</h1>
        <?php
        return;
    }

    $user = Users::getByIdLoaded(CurrentUser::$id);
    $values = $user->data;
    ?>
    <div class="user_edit">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="user" name="writemodule" />
            <input type="hidden" value="restore_password" name="action" />
            <input type="hidden" value="<?php echo $values['id']; ?>" name="id" />
            <div class="block">
                <div class="head">Смена пароля</div>
                <div class="data">
                    <div class="title">Новый пароль<?php input_error($data, 'new_1', 'edit'); ?></div>
                    <div class="value">
                        <input name="new_1" type="password">
                    </div>
                </div>
                <div class="data">
                    <div class="title">Новый пароль (ещё раз)<?php input_error($data, 'new_2', 'edit'); ?></div>
                    <div class="value">
                        <input name="new_2" type="password">
                    </div>
                </div>
            </div>
            <div class="block"><input type="submit" class="submit" value="сохранить" /></div>
        </form>
    </div><?
}

function tp_user_show_forget($data) {
    $values = isset($data['write']['value_forget']) ? $data['write']['value_forget'] : array();
    if (isset($data['write']['success'])) {
        ?>
        <div class="user_forget"><h2>Ссылка для восстановления пароля выслана на Ваш E-mail</h2></div>
        <?php
        return;
    }
    ?><div class="user_forget">
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" value="user" name="writemodule" />
            <input type="hidden" value="forget" name="action" />
            <div class="block">
                <div class="head">Данные для восстановления пароля</div>
                <div class="data">
                    <div class="title">E-mail:<?php input_error($data, 'email', 'forget'); ?></div>
                    <div class="value">
                        <input name="email" value="<?php input_val($data, $values, 'email', 'edit') ?>">
                    </div>
                </div>
            </div>
            <div class="block"><input type="submit" class="submit" value="Восстановить" /></div>
        </form>
    </div><?php
}

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
                <div class="head">Социальные сети</div>
                <div class="data">
                    <div class="title">Вконтакте</div>
                    <div class="value">
                        <?php
                        $vk_txt = 'не привязан';
                        if ($values['vk_id']) {
                            $vk_txt = $value['vk_name'] ? $value['vk_name'] : 'http://vk.com/id' . $values['vk_id'];
                        }
                        ?>
                        <span class="vk"><a onclick="change_vk_profile()"><?php echo $vk_txt; ?></a></span>
                    </div>
                </div>

            </div>
            <div class="block">
                <div class="head">Фотография профиля</div>
                <div class="data">
                    <div class="title">
                        <img src="<?php echo $user->getAvatar(true) ?>" />
                    </div>
                    <div class="value">
                        <input name="userpic" type="file">
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="head">Смена пароля</div>
                <div class="data">
                    <div class="title">Текущий пароль<?php input_error($data, 'old', 'edit'); ?></div>
                    <div class="value">
                        <input name="old" type="password">
                    </div>
                </div>
                <div class="data">
                    <div class="title">Новый пароль<?php input_error($data, 'new_1', 'edit'); ?></div>
                    <div class="value">
                        <input name="new_1" type="password">
                    </div>
                </div>
                <div class="data">
                    <div class="title">Новый пароль (ещё раз)<?php input_error($data, 'new_2', 'edit'); ?></div>
                    <div class="value">
                        <input name="new_2" type="password">
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

function tp_user_show_points($data) {
    ?><div class="user_show_points rounded_3_shadow">
        <div class="cur">У Вас <?php echo $data['data']['points'] ?> баллов</div>
        <div class="list">
            <?php foreach ($data['history'] as $line) {
                ?>
                <div class="line">
                    <span class="time"><?php echo date('Y-m-d H:i', $line['time']); ?></span>
                    <span class="points"><?php echo $line['points']; ?></span>
                    <span class="mes"><?php echo $line['message']; ?></span>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

function tp_user_show_badges($data) {
    ?><div class="user_badges"><?php
    foreach ($data['badges'] as $badge_id => $badge) {
        $state = 'none';
        $progress = 0;
        $title = $badge['title'];
        $descr = $badge['title_for'];
        $points = $badge['points'];
        if (isset($badge['points'])) {
            if ($badge['user_data']['gained_time']) {
                $state = 'finished';
                $progress = (int) $badge['user_data']['progress'];
            } else {
                $state = 'current';
                $progress = (int) $badge['user_data']['progress'];
            }
        }
        ?><div title="<?php echo $descr; ?>" alt="<?php echo $descr; ?>" class="badge badge_type<?php echo $badge_id; ?> badge_state_<?php echo $state; ?>">
                                                        <!--div class="progress"><?php echo $progress; ?></div-->
                <div class="title"><?php echo $title; ?></div>
                <!--div class="descr"><?php echo $descr; ?></div-->
                <div class="points"><?php echo $points; ?></div>
            </div><?php
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
        <div class="info">
            <i>зарегистрирован</i><span><?php echo date('d.m.Y', $user->data['registerTime']); ?></span>
            <i>последний раз был</i><span><?php echo date('d.m.Y', $user->data['lastAccessTime']); ?></span>
            <?php if ($self) { ?>
                <i>бонусов накоплено</i><span><a href="/u/<?php echo $user->id; ?>/points"><?php echo $user->data['points']; ?></a></span>
            <?php } ?>

            <i>награды</i><span><a href="/u/<?php echo $user->id; ?>/badges">смотреть</a></span>

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
            //'publication' => 'Публикации',
            //'albums' => 'Альбомы',
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
            <a href="/forget">забыли пароль?</a>
        </form>
    </div>
    <?php
}

function th_draw_notify($notifyes) {
    ?>
    <div class="notify">
        <div class="cnt"><span class="n"><?php echo count($notifyes); ?></span><span class="t"><?php echo declOfNum(count($notifyes), array('уведомление', 'уведомления', 'уведомлений')) ?> для Вас</span></div>
        <div class="notifyes">
            <?php foreach ($notifyes as $notify) {
                ?>
                <div class="notify">
                    <div class="img"><img src="<?php echo $notify['img'] ?>"></div><div class="title"><a href="<?php echo $notify['url'] ?>"><?php echo $notify['title'] ?></a></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
}

function draw_user_logged_plank($data) {
    $user = Users::getByIdLoaded(CurrentUser::$id);
    ?>
    <div class="plank_logged">
        <span>Вы вошли как</span><a class="name" href="/u/<?php echo $user->data['id'] ?>"><?php echo th_username($user->data) ?></a><a class="logout" href="/logout">выйти</a>
        <?php
        if (isset($data['notify'])) {
            th_draw_notify($data['notify']);
        }
        ?>
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
        <h1>Зарегистрируйтесь, чтобы завести свой альбом или участвовать в конкурсах</h1>
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
            <div class="agree_">
                <span class="agree">Согласен с условиями <a href="/agreement">Пользовательского соглашения</a>.</span>
                <input type="checkbox" value="1" name="agree">
                <?php input_error($data, 'agree', 'register'); ?>
            </div>
        </form>
    </div>
    <?php
}