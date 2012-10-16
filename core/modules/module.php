<?php

class module {

    function process($action, $mode) {
        return $this->_process($action, $mode);
    }

    function _process($action, $mode){
        throw new Exception('must be implemented');
    }

}