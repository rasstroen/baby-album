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

$autoload = false;
require_once $core_path . 'include.php';


require_once $core_path . 'Database.php';
require_once $core_path . 'classes/ImgStore.php';
require_once $core_path . 'classes/Amazon.php';

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
            // IF IMAGE ON OUR SERVER
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
            // IF IMAGE ON AMAZON
            if ($image['server_id'] == ImgStore::SERVER_AMAZONS3) {
                if ($image['stored_local_real']) {
                    // its copied locally
                    $real_path = ImgStore::getFileLocalPath($image['image_id'], $image['size_id']);
                    if (is_readable($real_path)) {
                        log_('Local copy found at ' . $real_path . ', will delete from amazon');
                        $amazon_fetch = false;
                    } else {
                        log_('Local copy missed at ' . $real_path . ' will fetched from amazon');
                        $amazon_fetch = true;
                    }
                } else {
                    log_('Local copy not placed at local, will fetched from amazon');
                    $amazon_fetch = true;
                }

                if ($amazon_fetch) {
                    // fetch from amazon
                    $fetch_url = ImgStore::getUrl($image['image_id'], $image['size_id'], 0);
                    $real_private_path = ImgStore::getFileLocalPath($image['image_id'], $image['size_id'], $private = true);
                    log_('fetching ' . $fetch_url . ' to ' . $real_private_path);
                    file_put_contents($real_private_path, file_get_contents($fetch_url));
                    log_($image['bytes'] . '=db size,' . filesize($real_private_path) . '=real size copied');
                    if (filesize($real_private_path)) {
                        $amazon_dest = str_replace(ImgStore::$server_urls[ImgStore::SERVER_AMAZONS3], '', $fetch_url);
                        $res = Amazon::delete($amazon_dest);
                        log_('deleted ' . $amazon_dest . ' from amazon with code:' . $res);
                        Database::query('UPDATE `images` SET `server_id`=' . ImgStore::SERVER_PRIVATE . ', `private_real`=1 WHERE `id`=' . $image['id']);
                    }
                } else {
                    // get from local file, delete amazon copy
                    $amazon_dest = str_replace(ImgStore::$server_urls[ImgStore::SERVER_AMAZONS3], '', ImgStore::getUrl($image['image_id'], $image['size_id'], 0));
                    $res = Amazon::delete($amazon_dest);
                    log_('deleted ' . $amazon_dest . ' from amazon with code:' . $res);
                    //
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
        }
    }
}

function log_($txt) {
    echo date('Y-m-d H:i:s') . ' ' . $txt . "\n";
}