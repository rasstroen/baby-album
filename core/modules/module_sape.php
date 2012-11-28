<?php

/**
 *
 * @author mchubar
 */
class module_sape extends module {

    function process($action, $mode) {
        if (!defined('_SAPE_USER')) {
            define('_SAPE_USER', 'b83ffc8f07e480f8261a893a385574ad');
        }
        require_once($_SERVER['DOCUMENT_ROOT'] . '/' . _SAPE_USER . '/sape.php');
        $sape = new SAPE_client(array('charset' => 'UTF-8', 'force_show_code' => false));
        $i = 0;
        while ($i < 6 && ($r = $sape->return_links()) != '') {
            $i++;
            $data['links'][] = $r;
        }
        return $data;
    }

}