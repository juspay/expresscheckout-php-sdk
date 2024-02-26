<?php

namespace Juspay;

/**
 * Class JuspayEnvironment
 *
 * @package Juspay
 */
class JuspayEnvironment {
    
    // Constatnts
    const DEVELOPMENT_BASE_URL = 'https://localapi.juspay.in';
    const SANDBOX_BASE_URL = 'https://sandbox.juspay.in';
    const PRODUCTION_BASE_URL = 'https://api.juspay.in';
    
    // Static variables
    /**
     *
     * @property string
     */
    private static $apiKey;
    /**
     *
     * @property string
     */
    private static $apiVersion;
    /**
     *
     * @property string
     */
    private static $baseUrl;
    /**
     *
     * @property int
     */
    private static $connectTimeout;
    /**
     *
     * @property int
     */
    private static $readTimeout;
    /**
     *
     * @property string
     */
    private static $sdkVersion;
    /**
     *
     * @property JuspayEnvironment
     */
    private static $thisObj;

    /**
     *
     * @property string $merchantId
     */
    private static $merchantId;

    /**
     * Initializes the Juspay ExpressCheckout payment environment with default
     * values and returns a singleton object of JuspayEnvironment class.
     *
     * @return JuspayEnvironment
     */
    static function init() {
        if (self::$thisObj != null) {
            return self::$thisObj;
        } else {
            self::$apiKey = '';
            self::$apiVersion = '2016-10-27';
            self::$baseUrl = JuspayEnvironment::PRODUCTION_BASE_URL;
            self::$connectTimeout = 15;
            self::$readTimeout = 30;
            self::$sdkVersion = file_get_contents ( __DIR__ . '/../VERSION' );
            self::$thisObj = new JuspayEnvironment ();
            return self::$thisObj;
        }
    }
    
    /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given API Key.
     *
     * @param string $apiKey
     *
     * @return JuspayEnvironment
     */
    public function withApiKey($apiKey) {
        self::$apiKey = $apiKey;
        return $this;
    }
    
    /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given Base URL.
     *
     * @param string $baseUrl
     *
     * @return JuspayEnvironment
     */
    public function withBaseUrl($baseUrl) {
        self::$baseUrl = $baseUrl;
        return $this;
    }
    
    /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given connect timeout.
     *
     * @param int $connectTimeout
     *
     * @return JuspayEnvironment
     */
    public function withConnectTimeout($connectTimeout) {
        self::$connectTimeout = $connectTimeout;
        return $this;
    }
    
    /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given read timeout.
     *
     * @param int $readTimeout
     *
     * @return JuspayEnvironment
     */
    public function withReadTimeout($readTimeout) {
        self::$readTimeout = $readTimeout;
        return $this;
    }

     /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given merchant id.
     *
     * @param string $merchantId
     *
     * @return JuspayEnvironment
     */
    public function withMerchantId($merchantId) {
        self::$merchantId = $merchantId;
        return $this;
    }
    
    /**
     *
     * @return string
     */
    public static function getApiKey() {
        return self::$apiKey;
    }
    
    /**
     *
     * @return string
     */
    public static function getApiVersion() {
        return self::$apiVersion;
    }
    
    /**
     *
     * @return string
     */
    public static function getBaseUrl() {
        return self::$baseUrl;
    }
    
    /**
     *
     * @return int
     */
    public static function getConnectTimeout() {
        return self::$connectTimeout;
    }
    
    /**
     *
     * @return int
     */
    public static function getReadTimeout() {
        return self::$readTimeout;
    }
    
    /**
     *
     * @return string
     */
    public static function getSdkVersion() {
        return self::$sdkVersion;
    }

    /**
     *
     * @return string
     */
    public static function getMerchantId() {
        return self::$merchantId;
    }
}