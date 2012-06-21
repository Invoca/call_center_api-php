<?php

require_once 'Curl_Request.php';

class CurlRequestMock extends CurlRequest
{

    public function init($url){
    	$this->called_init = true;
    }

    public function setOption($name, $value) {
        $this->addOption($name, $value);
    }

    public function execute() {
        $this->called_execute = true;
        return '';
    }

    public function getInfo($name) {
        return '200';
    }

    public function close() {
    }
}