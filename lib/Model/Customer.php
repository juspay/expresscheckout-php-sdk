<?php

namespace Juspay\Model;

use Juspay\RequestMethod;
use Juspay\Exception\InvalidRequestException;

class Customer extends JuspayEntity {

	public $id;
	public $object;
	public $firstName;
	public $lastName;
	public $mobileCountryCode;
	public $mobileNumber;
	public $emailAddress;
	public $dateCreated;
	public $lastUpdated;
	public $objectReferenceId;

	public function __construct($params) {
		foreach (array_keys($params) as $key) {
			$newKey = $this->camelize($key);
			if($newKey == "dateCreated" || $newKey == "lastUpdated") {
				$this->$newKey = date_create($params[$key]);
			} else {
				$this->$newKey = $params[$key];
			}
		}
	}
	
	public static function create($params, $requestOptions=null) {
	if ($params == null || count($params) == 0) {
    		throw new InvalidRequestException();
    	}
		$response = self::makeServiceCall("/customers", $params, RequestMethod::POST, $requestOptions);
		return new Customer($response);
	}

	public static function update($id, $params, $requestOptions=null) {
		if ($id == null || $id == "" || $params == null || count($params) == 0) {
			throw new InvalidRequestException();
		}
		$response = self::makeServiceCall("/customers/".$id, $params, RequestMethod::POST, $requestOptions);
		return new Customer($response);
	}

	public static function listAll($params, $requestOptions=null) {		
		$response = self::makeServiceCall("/customers", $params, RequestMethod::GET, $requestOptions);
		return new CustomerList($response);
	}

	public static function get($id, $requestOptions=null) {
		if ($id == null || $id == "") {
			throw new InvalidRequestException();
		}
		$response = self::makeServiceCall("/customers/".$id, null, RequestMethod::GET, $requestOptions);
		return new Customer($response);
	}
}
