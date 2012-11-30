<?php

/**
 * Если поле private файла не совпадает с полем private_real
 * Делаем так, чтобы они совпадали
 * (переносим файл в приватную зону или забираем его оттуда)
 */
$dev_mode = false;
$core_path = '/home/balbum.ru/core/';
ini_set('display_errors', $dev_mode);
require_once $core_path . 'config.php';
require_once $core_path . 'include.php';
$sleeps = 0;
if (!is_running_process(basename(__FILE__))) {
    while (true) {
        $query = 'SELECT * FROM `images` WHERE `private`=1 AND `private_real`=0 AND `deleted`=0';
        $to_private = Database::sql2array($query);
        log_(count($to_private) . ' images to move');
        if (!count($to_private)) {
            log_('sleep');
            sleep(1);
            $sleeps++;
            if ($sleeps > 30)
                exit(0);
        }
        foreach ($to_private as $image) {
            if ($image['server_id'] == ImgStore::SERVER_PRIVATE) {
                log_($image['id'] . ' already in private');
                Database::query('UPDATE `images` SET `private_real`=1 WHERE `id`=' . $image['id']);
            }
            if ($image['server_id'] == ImgStore::SERVER_ORIG) {
                $real_path = ImgStore::getFileLocalPath($image['image_id'], $image['size_id']);
                log_($image['id'] . ' at original server in ' . $real_path . ' ' . filesize($real_path) . ' bytes');
                // change location to private folder
                $real_private_path = ImgStore::getFileLocalPath($image['image_id'], $image['size_id'], $private = true);
                log_('will be moved to ' . $real_private_path);

                if (copy($real_path, $real_private_path)) {
                    log_('succesfully copied');
                    Database::query('UPDATE `images` SET `server_id`=' . ImgStore::SERVER_PRIVATE . ', `private_real`=1 WHERE `id`=' . $image['id']);
                    unlink($real_path);
                } else {
                    log_('cant copy');
                }
            }
        }
        die();
    }
}

function log_($txt) {
    echo date('Y-m-d H:i:s') . ' ' . $txt . "\n";
}