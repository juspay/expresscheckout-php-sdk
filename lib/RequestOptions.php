<?php

namespace Juspay;

class RequestOptions {
    
    /**
     *
     * @property string
     */
    private $apiKey;
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->apiKey = JuspayEnvironment::getApiKey ();
    }
    
    /**
     * Returns a RequestOptions object with default values
     * from JuspayEnvironment object.
     *
     * @return RequestOptions
     */
    public static function createDefault() {
        JuspayEnvironment::init ();
        return new RequestOptions ();
    }
    
    /**
     * Initializes the RequestOptions object with given API Key.
     *
     * @param string $apiKey
     *
     * @return RequestOptions
     */
    public function withApiKey($apiKey) {
        $this->apiKey = $apiKey;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }
}