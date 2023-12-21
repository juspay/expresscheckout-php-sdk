<?php

namespace Juspay;
use Juspay\Model\IJuspayJWT;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class JuspayLogLevel {
    const Debug = 1;
    const Error = 2;
    const Info = 3;

    const Off = 4;
}
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
     * @property string
     */
    private static $caCertificatePath;
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
     * @property IJuspayJWT $JuspayJWT
     */
    private static $JuspayJWT;

       /**
     *
     * @property string $merchantId
     */
    private static $merchantId;
     /**
     *
     * @property JuspayLogLevel $logLevel
     */
    private static $logLevel;

      /**
     *
     * @property Logger  $logger
     */
    public static $logger;

        /**
     *
     * @property string  $logFilePath
     */
    public static $logFilePath;

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
            self::buildLogger();
            self::$thisObj = new JuspayEnvironment ();
            return self::$thisObj;
        }
    }
    
    static function buildLogger()
    {
        self::$logger = new Logger("expresscheckout");
        $dateFormat = "Y-m-d H:m:s.v";
        $output = "%datetime% - %level_name% - %message%\n";
        $logFilePath = self::$logFilePath == null ? 'log/juspay_sdk.log' : self::$logFilePath;
        $stream = new RotatingFileHandler($logFilePath, 1, self::getMonoLogLevel());
        $console = new StreamHandler("php://stdout",  self::getMonoLogLevel());
        $formatter = new LineFormatter($output, $dateFormat);
        $stream->setFormatter($formatter);
        $console->setFormatter($formatter);
        self::$logger->pushHandler($stream);
        self::$logger->pushHandler($console);
    }

    static function getMonoLogLevel() {
        switch (self::getLogLevel()) {
            case JuspayLogLevel::Debug:
                return Logger::DEBUG;
            case JuspayLogLevel::Error:
                return Logger::ERROR;
            case JuspayLogLevel::Info:
                return Logger::INFO;
            case JuspayLogLevel::Off:
                return Logger::EMERGENCY;
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
     * with given ca certificate.
     *
     * @param string $path
     *
     * @return JuspayEnvironment
     */
    public function withCACertificatePath($path) {
        if ($path == null) {
            self::$caCertificatePath = null;
            return $this;
        }
        self::$caCertificatePath = realpath($path);
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
     * Initializes the Juspay ExpressCheckout payment environment
     * with given Juspay JWT.
     *
     * @param IJuspayJWT $juspayJWT
     *
     * @return JuspayEnvironment
     */
    public function withJuspayJWT($juspayJWT) {
        self::$JuspayJWT = $juspayJWT;
        return $this;
    }

     /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given JuspayLogLevel.
     *
     * @param int $logLevel
     *
     * @return JuspayEnvironment
     */
    public function withLogLevel($logLevel) {
        if ($logLevel == null) {
            self::$logLevel = JuspayLogLevel::Off;
            self::buildLogger();
            return $this;
        }
        self::$logLevel = $logLevel;
        self::buildLogger();
        return $this;
    }

     /**
     * Initializes the Juspay ExpressCheckout payment environment
     * with given LogFilePath.
     *
     * @param string $logFilePath
     *
     * @return JuspayEnvironment
     */
    public function withLogFilePath($logFilePath) {
        self::$logFilePath = $logFilePath;
        self::buildLogger();
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
    public static function getCACertificatePath() {
        return self::$caCertificatePath;
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

     /**
     *
     * @return IJuspayJWT
     */
    public static function getJuspayJWT() {
        return self::$JuspayJWT;
    }

    /**
     *
     * @return int
    */
    public static function getLogLevel() {
        if (self::$logLevel == null) {
            self::$logLevel = JuspayLogLevel::Off;
        }
        return self::$logLevel;
    }

    /**
     *
     * @return string
     */
    public static function getLogFilePath() {
        return self::$logFilePath;
    }
}