<?php

/**
 *
 * @author mchubar
 */
$dev_mode = false;
$core_path = '/home/balbum.ru/core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
require_once $core_path . 'include.php';

if (!is_running_process(basename(__FILE__))) {
    while (true) {
        $to_process = Database::sql2array('SELECT * FROM `user_badges_actions` ORDER BY `time` LIMIT 100');
        if (count($to_process)) {
            foreach ($to_process as $action) {
                echo "\n=========\n" . date('Y-m-d H:i:s') . "\t" . date('Y-m-d H:i:s', $action['time']) . " uid=" . $action['user_id'] . " bid=" . $action['badge_type_id'] . "\n";
                // get user progress in action badge_type
                $current = Database::sql2array('SELECT * FROM `user_badges` WHERE `user_id`=' . $action['user_id'] . ' AND `badge_type_id`=' . $action['badge_type_id'], 'badge_id');
                $current = $current ? $current : array();
                // какой максимальный прогресс у юзера с этим бейджем?
                $total_progress = 0;
                $last_badge_id = 0;
                foreach ($current as $line) {
                    $total_progress = max($total_progress, $line['progress']);
                    $last_badge_id = max($last_badge_id, $line['badge_id']);
                }
                echo "TOTAL PROGRESS FOR USER#" . $action['user_id'] . "\t BADGE#" . $action['badge_type_id'] . "\t= " . $total_progress . "\n";
                // добавляем прогресс
                if ($action['progress_set']) {
                    $total_progress = $action['progress_set'];
                } else {
                    $total_progress = $total_progress + $action['progress_add'];
                }
                echo "SETTING TOTAL PROGRESS " . $total_progress . "\n";
                // calculating all badges in line
                $existingBadges = Badges::getBadgesLine($action['badge_type_id'], $total_progress, $full = true);
                $found_next = false;
                foreach ($existingBadges as $id => $existingBadge) {
                    // нет такого или есть, но прогресс не позволял получить
                    if ($total_progress >= $existingBadge['repeat']) {
                        if (!isset($current[$id]) || (!$current[$id]['gained_time'])) {
                            echo "BADGE RECEIVED:" . $id;
                            $last_badge_id = Badges::addBadge($action['user_id'], $action['badge_type_id'], $id, $total_progress);
                        }
                    }
                    if (!$found_next && ($total_progress < $existingBadge['repeat'])) {
                        $found_next = true;
                        echo "NEXT BADGE " . $id . " TO STORE \n";
                        $last_badge_id = $id;
                    }
                }
                // updating progress
                if (!$last_badge_id) {
                    // не было прогресса по этому бейджу
                    echo "NEW BADGE TO STORE\n";
                    $last_badge_id = Badges::getFirstBadgeId($action['badge_type_id']);
                }

                Badges::addBadgeStored($action['user_id'], $action['badge_type_id'], $last_badge_id, $total_progress);
                // deleting row
                Database::query('DELETE FROM `user_badges_actions` WHERE `user_id`=' . $action['user_id'] . ' AND `badge_type_id`=' . $action['badge_type_id'] . ' AND `time`=' . $action['time']);
                // if it's no any badge - add badge
                // set total progress for line - updating last action in line
            }
        } else {
            echo "\nnothing to do\n";
            break;
        }
    }
}else
    echo "\nalready running\n";