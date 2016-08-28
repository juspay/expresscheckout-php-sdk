<?php
namespace Juspay;

class JuspayEnvironment {
	
	// Constatnts
	const DEVELOPMENT_BASE_URL = 'https://localapi.juspay.in';
	const SANDBOX_BASE_URL = 'https://sandbox.juspay.in';
	const PRODUCTION_BASE_URL = 'https://api.juspay.in';
	
	// Static variables
	private static $apiKey;
	private static $apiVersion;
	private static $baseUrl;
	private static $connectTimeout;
	private static $readTimeout;
	private static $sdkVersion;
	
	private static $thisObj;
	
	static function init()
	{
		require_once __DIR__.'/RequestOptions.php';
		require_once __DIR__.'/RequestMethod.php';
		require_once __DIR__.'/Model/JuspayEntity.php';
		require_once __DIR__.'/Model/JuspayEntitylist.php';
		require_once __DIR__.'/Model/Card.php';
		require_once __DIR__.'/Model/Customer.php';
		require_once __DIR__.'/Model/CustomerList.php';
		require_once __DIR__.'/Model/Order.php';
		require_once __DIR__.'/Model/OrderList.php';
		require_once __DIR__.'/Model/Payment.php';
		require_once __DIR__.'/Model/PaymentGatewayResponse.php';
		require_once __DIR__.'/Model/Refund.php';
		require_once __DIR__.'/Model/Wallet.php';
		require_once __DIR__.'/Model/WalletList.php';
		require_once __DIR__.'/Exception/JuspayException.php';
		require_once __DIR__.'/Exception/APIException.php';
		require_once __DIR__.'/Exception/APIConnectionException.php';
		require_once __DIR__.'/Exception/AuthenticationException.php';
		require_once __DIR__.'/Exception/InvalidRequestException.php';
		self::$apiKey = '';
		self::$apiVersion = '2016-07-19';
		self::$baseUrl = JuspayEnvironment::PRODUCTION_BASE_URL;
		self::$connectTimeout = 15;
		self::$readTimeout = 15;
		self::$sdkVersion = file_get_contents(__DIR__.'/../VERSION');
		if(self::$thisObj!=null) {
			return self::$thisObj;
		} else {
			return new JuspayEnvironment();
		}
	}
	
	public function withApiKey($apiKey) {
		self::$apiKey = $apiKey;
		return $this;
	}

	public function withBaseUrl($baseUrl) {
		self::$baseUrl = $baseUrl;
		return $this;
	}

	public function withConnectTimeout($connectTimeout) {
		self::$connectTimeout = $connectTimeout;
		return $this;
	}
	
	public function withReadTimeout($readTimeout) {
		self::$readTimeout = $readTimeout;
		return $this;
	}
	
	public static function getApiKey() {
		return self::$apiKey;
	}
	
	public static function getApiVersion() {
		return self::$apiVersion;
	}
	
	public static function getBaseUrl() {
		return self::$baseUrl;
	}

	public static function getConnectTimeout() {
		return self::$connectTimeout;
	}

	public static function getReadTimeout() {
		return self::$readTimeout;
	}
	
	public static function getSdkVersion() {
		return self::$sdkVersion;
	}
	
}