<?php

/**
 * 1. На каждое действие вешаем Badges::progressAction($user_id, $action_type, $params)
 * 2. Пишем в таблицу факт нового экшна от юзера
 * 3. Демон ходит по экшенам и учитывает их, записывая прогресс конкретного бейджа конкретному юзеру
 * 4. Даже если прогресс больше, чем надо - пишем прогресс в последний подходящий эвент, вдруг добавится бэйдж с прогрессом больше
 * 5. Если прогресс достаточен для получения следующего бейджа - выдаем бейдж демоном
 * 6. Никогда не удаляем прогресс бейджей юзера
 * @author mchubar
 */
class Badges {

    const POINT_TO_RUB = 0.1;

    private static $badges = array(
        self::ACTION_TYPE_REGISTER => array(
            100 => array(
                'title' => 'Регистрация на сайте',
                'title_for' => 'За регистрацию на сайте',
                'points' => 100,
                'repeat' => 1,
            )
        ),
        self::ACTION_TYPE_LOGIN_DAILY => array(
            200 => array(
                'title' => '2 дня подряд на сайте',
                'title_for' => 'За 2 дня подряд на сайте',
                'points' => 100,
                'repeat' => 2,
            ),
            201 => array(
                'title' => '5 дней подряд на сайте',
                'title_for' => 'За 5 дней подряд на сайте',
                'points' => 200,
                'repeat' => 5,
            ),
            202 => array(
                'title' => '2 недели на сайте',
                'title_for' => 'За 2 недели подряд на сайте',
                'points' => 500,
                'repeat' => 14,
            ),
        ),
        self::ACTION_TYPE_ADD_EVENT => array(
            300 => array(
                'title' => 'Добавление события',
                'title_for' => 'За добавление события',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_ADD_THEMED_EVENT => array(
            400 => array(
                'title' => 'Добавление темизированного события',
                'title_for' => 'За добавление особого события',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_ADD_PHOTO => array(
            500 => array(
                'title' => 'Добавление фотографии',
                'title_for' => 'За добавление фотографии',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_EDIT_PROFILE => array(
            600 => array(
                'title' => 'Заполнения профиля',
                'title_for' => 'За заполнения профиля',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_LIKE => array(
            700 => array(
                'title' => 'Мне нравится',
                'title_for' => 'За лайк чужой фотографии',
                'points' => 100,
                'repeat' => 5,
            ),
            701 => array(
                'title' => 'Мне очень нравится',
                'title_for' => 'За лайк 10 чужих фотографий',
                'points' => 200,
                'repeat' => 10,
            ),
            702 => array(
                'title' => 'Мне дико нравится',
                'title_for' => 'За лайк 100 чужих фотографий',
                'points' => 500,
                'repeat' => 10,
            ),
        ),
        self::ACTION_TYPE_LIKED => array(
            800 => array(
                'title' => 'Им нравится!',
                'title_for' => 'За лайк моей фотографии',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_COMMENT => array(
            900 => array(
                'title' => 'Моё мнение',
                'title_for' => 'За комментарий',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
        self::ACTION_TYPE_COMMENTED => array(
            1000 => array(
                'title' => 'Чужое мнение',
                'title_for' => 'За комментарий моей фотографии',
                'points' => 100,
                'repeat' => 1,
            ),
        ),
    );

    const ACTION_TYPE_REGISTER = 9; // регистрация
    const ACTION_TYPE_LOGIN_DAILY = 10; // считаем ежедневные логины
    const ACTION_TYPE_ADD_EVENT = 20; // считаем добавление эвента
    const ACTION_TYPE_ADD_THEMED_EVENT = 30; // считаем добавление эвента с темой
    const ACTION_TYPE_ADD_PHOTO = 40; // считаем добавление фотографий
    const ACTION_TYPE_EDIT_PROFILE = 50; // считаем редактирования профиля
    const ACTION_TYPE_LIKE = 60; // лайки
    const ACTION_TYPE_LIKED = 70; // меня лайкнули
    const ACTION_TYPE_COMMENT = 80; // добавление коммента
    const ACTION_TYPE_COMMENTED = 90; // меня откомментили

    public static function getFirstBadgeId($action_type) {
        if (!isset(self::$badges[$action_type]))
            return false;
        if (!count(self::$badges[$action_type]))
            return false;
        return array_shift(array_keys(self::$badges[$action_type]));
    }

    public static function getBadgesLine($action_type, $progress, $full = false) {
        $ret = array();
        if (!isset(self::$badges[$action_type]))
            return $ret;
        if (!count(self::$badges[$action_type]))
            return $ret;
        foreach (self::$badges[$action_type] as $id => $badge) {
            if ($full || ($badge['repeat'] <= $progress))
                $ret[$id] = $badge;
        }
        return $ret;
    }

    private static function preprocessAction($user_id, $action_type, $progress = 0) {
        switch ($action_type) {
            default:
                $progress_set = $progress ? $progress : 0;
                $progress_add = $progress ? 0 : 1;
                break;
        }
        return array($progress_set, $progress_add);
    }

    public static function progressAction($user_id, $action_type, $progress = 0) {

        if (!isset(self::$badges[$action_type]))
            return false;
        if (!count(self::$badges[$action_type]))
            return false;

        list($progress_set, $progress_add) = self::preprocessAction($user_id, $action_type, $progress);

        Database::query('INSERT INTO `user_badges_actions` SET
            `user_id`=' . $user_id . ',
            `badge_type_id`=' . $action_type . ',
            `progress_set`=' . $progress_set . ',
            `progress_add`=' . $progress_add . ',
            `processed`=0,
            `time`=' . time());
    }

    public static function getUserBadges($user_id) {

    }

    public static function getUserPoints($user_id) {

    }

    public static function getUserAllBadges($user_id) {
        $user_badges = Database::sql2array('SELECT * FROM `user_badges` WHERE `user_id`=' . $user_id, 'badge_id');
        foreach ($user_badges as $user_badge) {
            $progress = $user_badge['progress'];
            $next = false;
            foreach (self::$badges[$user_badge['badge_type_id']] as $id => $rows) {
                if (!$next) {
                    if ($rows['repeat'] > $progress) {
                        $next = true;
                        $user_badges_prepared[$id] = array(
                            'user_id' => $user_id,
                            'badge_type_id' => $user_badge['badge_type_id'],
                            'badge_id' => $id,
                            'update_time' => 0,
                            'progress' => $progress,
                            'left' => $rows['repeat'] - $progress,
                            'gained_time' => 0,
                            'accepted_time' => 0,
                            'message' => $rows['title_for'],
                            'points_gained' => $rows['points'],
                        );
                    }
                }
            }
            $user_badges_prepared[$user_badge['badge_id']] = $user_badge;
        }
        $badges = self::$badges;
        $out = array();
        foreach ($badges as $action_type_id => $badges_at) {
            foreach ($badges_at as $key => $badge_at) {
                $out[$key] = $badge_at;
                if (isset($user_badges_prepared[$key])) {
                    $out[$key]['user_data'] = $user_badges_prepared[$key];
                }else
                    $out[$key]['user_data'] = false;
            }
        }
        return $out;
    }

    private static function addPoints($user_id, $points_count, $message) {
        Database::query('INSERT INTO `user_points_log` SET `time`=' . time() . ', `points`=' . $points_count . ' , `user_id`=' . $user_id . ', `message`=' . Database::escape($message));
        Database::query('UPDATE `user` SET `points`=`points`+' . $points_count . ' WHERE `id`=' . $user_id);
    }

    private static function decPoints($user_id, $points_count, $message) {
        Database::query('INSERT INTO `user_points_log` SET `time`=' . time() . ', `points`=' . $points_count . ' , `user_id`=' . $user_id . ', `message`=' . Database::escape($message));
        Database::query('UPDATE `user` SET `points`=`points`-' . $points_count . ' WHERE `id`=' . $user_id);
    }

    public static function addBadge($user_id, $badge_type_id, $badge_id, $total_progress) {

        Database::query('START TRANSACTION');
        $target_badge = self::$badges[$badge_type_id][$badge_id];
        $points_gained = $target_badge['points'];
        $message = $target_badge['title_for'];


        self::addPoints($user_id, $points_gained, $message);
        Database::query('INSERT INTO `user_badges` SET
            `user_id`=' . $user_id . ',
            `badge_type_id`=' . $badge_type_id . ',
            `badge_id`=' . $badge_id . ',
            `gained_time`=' . time() . ',
                `progress` = ' . $total_progress . ',
            `accepted_time` =0,
            `message` =' . Database::escape($message) . ',
            `points_gained`=' . $points_gained . '
                ON DUPLICATE KEY UPDATE
             `user_id`=' . $user_id . ',
            `badge_type_id`=' . $badge_type_id . ',
            `badge_id`=' . $badge_id . ',
            `gained_time`=' . time() . ',
                `progress` = ' . $total_progress . ',
            `accepted_time` =0,
            `message` =' . Database::escape($message) . ',
            `points_gained`=' . $points_gained . '');
        Database::query('COMMIT');
        return $badge_id;
    }

    public static function addBadgeStored($user_id, $badge_type_id, $badge_id, $progress) {
        Database::query('START TRANSACTION');
        $target_badge = self::$badges[$badge_type_id][$badge_id];
        $points_gained = $target_badge['points'];
        $message = $target_badge['title_for'];

        Database::query('INSERT INTO `user_badges` SET
            `user_id`=' . $user_id . ',
            `badge_type_id`=' . $badge_type_id . ',
            `badge_id`=' . $badge_id . ',
            `update_time`=' . time() . ',
            `progress` = ' . $progress . ',
            `gained_time`=0,
            `accepted_time` =0,
            `message` =' . Database::escape($message) . ',
            `points_gained`=0
                ON DUPLICATE KEY UPDATE
            `progress` = ' . $progress);
        Database::query('COMMIT');
        return $badge_id;
    }

}