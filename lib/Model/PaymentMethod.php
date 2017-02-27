<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\RequestMethod;

/**
 * Class Customer
 *
 * @property string $paymentMethod
 * @property string $paymentMethodType
 * @property string $description
 *
 * @package Juspay\Model
 */
class PaymentMethod extends JuspayEntity {
    
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params) {
        foreach ( array_keys ( $params ) as $key ) {
            $newKey = $this->camelize ( $key );
                $this->$newKey = $params [$key];
        }
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return PaymentMethod
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function create($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers", $params, RequestMethod::POST, $requestOptions );
        return new Customer ( $response );
    }
    
    /**
     *
     * @param string $id
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Customer
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function update($id, $params, $requestOptions = null) {
        if ($id == null || $id == "" || $params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers/" . $id, $params, RequestMethod::POST, $requestOptions );
        return new Customer ( $response );
    }
    
    /**
     *
     * @param string $merchantId
     * @param RequestOptions|null $requestOptions
     *
     * @return PaymentMethodList
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function listAll($merchantId, $requestOptions = null) {
        if ($merchantId == null || $merchantId == "") {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/merchants/".$merchantId."/paymentmethods",null, RequestMethod::GET, $requestOptions );
        return new PaymentMethodList ( $response );
    }
    
}
