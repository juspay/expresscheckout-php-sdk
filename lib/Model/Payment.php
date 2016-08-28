<?php

namespace Juspay\Model;

use Juspay\RequestMethod;
use Juspay\Exception\InvalidRequestException;

class Payment extends JuspayEntity {
	public $orderId;
	public $txnId;
	public $status;
	public $method;
	public $url;
	public $params;

	public function __construct($params) {
		foreach (array_keys($params) as $key) {
			$newKey = $this->camelize($key);
			$this->$newKey = $params[$key];
		}
	}
	
	public static function create($params, $requestOptions=null) {
		if ($params == null || count($params) == 0) {
			throw new InvalidRequestException();
		}
		// We will always send it as json in SDK.
		$params['format'] = "json";
		$response = self::makeServiceCall("/txns", $params, RequestMethod::POST, $requestOptions);
		$response = self::updatePaymentResponseStructure($response);
		return new Payment($response);
	}
	
	// Restructuring the payment response. Removed unnecessary hierarchy in the response.
	private static function updatePaymentResponseStructure($response) {
		$authResp = $response['payment']['authentication'];
		$response['method'] = $authResp['method'];
		$response['url'] = $authResp['url'];
		if($response['method']=="POST") {
			$response['params'] = array();
			foreach (array_keys($authResp['params']) as $key) {
				$response['params'][$key] = $authResp['params'][$key];
			}
		}
		unset($response['payment']);
		return $response;
	}
	
}

