<?php

namespace Juspay;

class RequestOptions {
    private $apiKey;
    private function __construct() {
        $this->apiKey = JuspayEnvironment::getApiKey ();
    }
    public static function createDefault() {
        JuspayEnvironment::init ();
        return new RequestOptions ();
    }
    public function getApiKey() {
        return $this->apiKey;
    }
    public function withApiKey($apiKey) {
        $this->apiKey = $apiKey;
        return $this;
    }
}