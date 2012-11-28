<?php

$dev_mode = false;
$core_path = '/home/balbum.ru/core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
require_once $core_path . 'include.php';

if (!is_running_process(basename(__FILE__))) {
    while (true) {
        $to_delete = array();
        // get task
        $query = 'SELECT * FROM `images` WHERE `ready`=0 ORDER BY `add_time` LIMIT 10';
        $tasks = Database::sql2array($query);
        if (!count($tasks)) {
            log_('no task');
            sleep(1);
            $sleeps++;
            log_('sleep');
            if($sleeps>10){
                log_('exit');
                exit(0);
            }
            continue;
        }

        log_(count($tasks) . ' images to process');

        $image_ids = array();
        foreach ($tasks as $task) {
            $image_ids[$task['image_id']] = $task['image_id'];
        }
        // get originals
        $query = 'SELECT * FROM `images` WHERE `is_orig`=1 AND `image_id` IN(' . implode(',', $image_ids) . ')';
        $originals = Database::sql2array($query, 'image_id');

        foreach ($tasks as $task) {
            echo "\n";
            if (isset($originals[$task['image_id']])) {
                $or = $originals[$task['image_id']];
                $orig_file_path = ImgStore::getFileLocalPath($or['image_id'], $or['size_id']);
                log_('task#' . $task['id'] . ' original imageid#' . $or['image_id'] . ' ' . $or['width'] . 'x' . $or['height'] . ', ' . (round($or['bytes'] / 1024 / 1024 * 1000) / 1000) . ' Mb');
                if (!is_readable($orig_file_path)) {
                    $to_delete[$task['id']] = $task['id'];
                    $to_error[$task['id']] = ImgStore::ERROR_NOT_READABLE;
                    log_($orig_file_path . ' is not readable');
                } else {
                    log_($orig_file_path . ' is readable, processing to ' . $task['width_requested'] . 'x' . $task['height_requested']);
                    $target_file_path = ImgStore::getFileLocalPath($task['image_id'], $task['size_id']);
                    $settings = array(
                        'width' => $or['width'],
                        'height' => $or['height'],
                        'crop_method' => $task['crop_method'],
                        'width_requested' => $task['width_requested'],
                        'height_requested' => $task['height_requested'],
                        'size_id' => $task['size_id'],
                    );
                    log_('saving to ' . $target_file_path);
                    list($new_width, $new_height, $target_width, $target_height) = ImgStore::resize($orig_file_path, $settings, $target_file_path);
                    $bytes = filesize($target_file_path);
                    log_('saved as ' . $new_width . 'x' . $new_height . '[requested ' . $target_width . 'x' . $target_height . '], ' . (round($bytes / 1024 / 1024 * 1000) / 1000) . ' Mb');
                    Database::query('UPDATE `images` SET 
                        `width`=' . $new_width . ',
                        `height`=' . $new_height . ',
                        `ready`=1,
                        `server_id`=' . ImgStore::SERVER_ORIG . ',
                        `bytes` =' . $bytes . ' WHERE `id`=' . $task['id']);
                }
            } else {
                log_('task# ' . $task['id'] . ' original missed!');
                $to_delete[$task['id']] = $task['id'];
            }
        }
    }
}

function log_($txt) {
    echo date('Y-m-d H:i:s') . ' ' . $txt . "\n";
}