<?php

namespace Juspay;
use Juspay\Model\IJuspayJWT;

class RequestOptions {
    
    /**
     *
     * @property string
     */
    private $apiKey;
    
    /**
     * Constructor
     */
    public function __construct(IJuspayJWT $juspayJWT = null) {
        $this->apiKey = JuspayEnvironment::getApiKey ();
        if ($juspayJWT != null) $this->JuspayJWT = $juspayJWT;
        else $this->JuspayJWT = JuspayEnvironment::getJuspayJWT();
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

    public $JuspayJWT;
}