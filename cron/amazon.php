<?php

$dev_mode = false;
$core_path = '/home/balbum.ru/core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
$autoload = false;
require_once $core_path . 'include.php';


require_once $core_path . 'Database.php';
require_once $core_path . 'classes/ImgStore.php';
require_once $core_path . 'classes/Amazon.php';



$day_limit_bytes = 100 * 1024 * 1024;
$day_limit_count = 100;


if (!is_running_process(basename(__FILE__))) {
    $day = floor(time() / (24 * 60 * 60));
    $limit = Database::sql2row('SELECT * FROM `amazon_limit` WHERE `day`=' . $day);
    if (!$limit) {
        Database::query('INSERT INTO `amazon_limit` SET `day`=' . $day . ', `uploaded_bytes`=0,`uploaded_count`=0');
        $limit = array(
            'uploaded_bytes' => 0,
            'uploaded_count' => 0,
        );
    }

    while (true) {
        $query = 'SELECT * FROM `images` WHERE
            `server_id`=1 AND
            `ready`=1 AND
            `private`=0 AND
            `deleted`=0 AND
            (`width`>1200 OR `height`>1200)
            ORDER BY `add_time` , `amazon_stored_time`
            LIMIT 10';
        $to_export = Database::sql2array($query);
        log_(count($to_export) . ' to export');
        if (!count($to_export)) {
            log_('no tasks left');
            exit(0);
        }
        foreach ($to_export as $image) {
            if ($limit['uploaded_bytes'] > $day_limit_bytes) {
                log_('LIMIT REACHED[BYTES]:' . $limit['uploaded_bytes'] . ' FROM ' . $day_limit_bytes);
                exit(0);
            } else {
                log_('LIMIT [BYTES]:' . $limit['uploaded_bytes'] . ' FROM ' . $day_limit_bytes);
            }
            if ($limit['uploaded_count'] > $day_limit_count) {
                log_('LIMIT REACHED[COUNT]:' . $limit['uploaded_count'] . ' FROM ' . $day_limit_count);
                exit(0);
            } else {
                log_('LIMIT [COUNT]:' . $limit['uploaded_count'] . ' FROM ' . $day_limit_count);
            }

            $real_path = ImgStore::getFileLocalPath($image['image_id'], $image['size_id']);
            log_($real_path . ' ' . (floor($image['bytes'] / 1024 / 1024 * 1000) / 1000) . 'Mb');

            $target_path = str_replace(ImgStore::$server_urls[ImgStore::SERVER_ORIG], '', ImgStore::getUrl($image['image_id'], $image['size_id'], 0));
            log_('saving to ' . $target_path);
            $result = Amazon::store($real_path, $target_path);
            if ($result) {
                $limit['uploaded_count']++;
                $limit['uploaded_bytes']+=$image['bytes'];
                log_('Stored at amazon at ' . ImgStore::$server_urls[ImgStore::SERVER_AMAZONS3] . $target_path);
                Database::query('UPDATE `images` SET 
                    `server_id`=' . ImgStore::SERVER_AMAZONS3 . ',
                    `amazon_stored_time`=' . time() . ' WHERE `id`=' . $image['id']);
            } else {
                log_('Cant store file to amazon');
                Database::query('UPDATE `images` SET `amazon_stored_time`=' . time() . ' WHERE `id`=' . $image['id']);
            }
            Database::query('REPLACE INTO `amazon_limit` SET `day`=' . $day . ', `uploaded_bytes`=' . $limit['uploaded_bytes'] . ',`uploaded_count`=' . $limit['uploaded_count']);
        }
        log_('+');
    }
}

function log_($txt) {
    echo date('Y-m-d H:i:s') . ' ' . $txt . "\n";
}