<?php

class AjaxQuery {

    function __construct($method, $params, $data) {
        return $thois->$method($params, $data);
    }

    function album_hide_suggest($params, &$data) {
        dpr($params);
    }

    function album_show_suggest($params, &$data) {
        dpr($params);
    }

}