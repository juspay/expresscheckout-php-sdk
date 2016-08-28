<?php

namespace Juspay\Model;

use Juspay\RequestMethod;
use Juspay\Exception\InvalidRequestException;

class Card extends JuspayEntity{
	
    public $cardNumber;
    public $nameOnCard;
    public $cardExpYear;
    public $cardExpMonth;
    public $cardSecurityCode;
    public $nickname;
    public $cardToken;
    public $cardReference;
    public $cardFingerprint;
    public $cardIsin;
    public $lastFourDigits;
    public $cardType;
    public $cardIssuer;
    public $savedToLocker;
    public $expired;
    public $cardBrand;
	
	public function __construct($params) {
		foreach (array_keys($params) as $key) {
			$newKey = $this->camelize($key);
			$this->$newKey = $params[$key];
		}
    }
    

    /***
     * Save a card and add it to the Juspay card locker.
     * Note that a locker needs to be enabled for a merchant account for a card to be saved.
     *
     * @param $params
     * @return array|mixed
     * @throws \InvalidArgumentException
     */
    public static function create($params, $requestOptions=null)
    {
    	if ($params == null || count($params) == 0) {
    		throw new InvalidRequestException();
    	}
    	$response = self::makeServiceCall('/card/add', $params, RequestMethod::POST, $requestOptions);
		return new Card($response);
    }
    
    /***
     * Lists all saved cards for a given customer.
     *
     * @param $customer_id
     * @return array|mixed
     */
    public static function listAll($params, $requestOptions=null)
    {
    	if ($params == null || count($params) == 0) {
    		throw new InvalidRequestException();
    	}
    	$response = self::makeServiceCall('/card/list', $params, RequestMethod::GET, $requestOptions);
    	$cardArray = array();
       	if (array_key_exists("cards", $response)) {
    		$cardArray = $response["cards"];
    		for ($i=0; $i<sizeof($cardArray); $i++) {
    			$cardArray[$i] = new Card($cardArray[$i]);
    		}
    	}
    	return $cardArray;

    }

    /***
     * Delete a saved card given the card token.
     *
     * @param $card_token
     * @return array|mixed
     */
    public static function delete($params, $requestOptions=null)
    {
    	if ($params == null || count($params) == 0) {
    		throw new InvalidRequestException();
    	}
    	$response = self::makeServiceCall('/card/delete', $params, RequestMethod::POST, $requestOptions);
    	return $response["deleted"];
    }
}
