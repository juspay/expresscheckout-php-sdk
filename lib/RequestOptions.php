<?php

namespace Juspay;

class RequestOptions {
    
    /**
     *
     * @property string
     */
    private $apiKey;

    /**
     * @property string $merchantId
     */
    private $merchantId;

    
    /**
     * Constructor
     */
    private function __construct() {
        $this->apiKey = JuspayEnvironment::getApiKey ();
        $this->merchantId = JuspayEnvironment::getMerchantId();
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
     * Initializes the RequestOptions object with given Merchant ID.
     *
     * @param string $merchantId
     *
     * @return RequestOptions
     */
    public function withMerchantId($merchantId) {
        $this->merchantId = $merchantId;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public function getApiKey() {
        return $this->apiKey;
    }
    
    /**
     *
     * @return string
     */
    public function getMerchantId() {
        return $this->merchantId;
    }
}