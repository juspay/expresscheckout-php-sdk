<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\RequestMethod;

/**
 * Class Order
 *
 * @property string $id
 * @property string $orderId
 * @property string $merchantId
 * @property string $txnId
 * @property float $amount
 * @property string $currency
 * @property string $customerId
 * @property string $customerEmail
 * @property string $customerPhone
 * @property string $description
 * @property string $productId
 * @property int $gatewayId
 * @property string $returnUrl
 * @property string $udf1
 * @property string $udf2
 * @property string $udf3
 * @property string $udf4
 * @property string $udf5
 * @property string $udf6
 * @property string $udf7
 * @property string $udf8
 * @property string $udf9
 * @property string $udf10
 * @property string $status
 * @property int $statusId
 * @property bool $refunded
 * @property float $amountRefunded
 * @property Refund[] $refunds
 * @property string $bankErrorCode
 * @property string $bankErrorMessage
 * @property string $paymentMethodType
 * @property string $paymentMethod
 * @property Card $card
 * @property PaymentGatewayResponse $paymentGatewayResponse
 * @property PaymentLinks $paymentLinks
 *
 * @package Juspay\Model
 */
class Order extends JuspayEntity {
    
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params) {
        foreach ( array_keys ( $params ) as $key ) {
            $newKey = $this->camelize ( $key );
            if ($newKey == "card") {
                $this->$newKey = new Card ( $params [$key] );
            } else if ($newKey == "paymentGatewayResponse") {
                $this->$newKey = new PaymentGatewayResponse ( $params [$key] );
            } else if ($newKey == "refunds") {
                $refunds = array ();
                for($i = 0; $i < count ( $params [$key] ); $i ++) {
                    $refunds [$i] = new Refund ( $params [$key] [$i] );
                }
                $this->$newKey = $refunds;
            } else if ($newKey == "paymentLinks") {
                $this->$newKey = new PaymentLinks ( $params [$key] );
            } else {
                $this->$newKey = $params [$key];
            }
        }
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Order
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
        $response = self::makeServiceCall ( "/order/create", $params, RequestMethod::POST, $requestOptions );
        $response = self::addInputParamsToResponse ( $params, $response );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Order
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function status($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/status", $params, RequestMethod::POST, $requestOptions );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Order
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function update($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/update", $params, RequestMethod::POST, $requestOptions );
        return new Order ( $response );
    }
    
    /**
     *
     * @param array|null $params
     * @param RequestOptions|null $requestOptions
     *
     * @return OrderList
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function listAll($params, $requestOptions = null) {
        $response = self::makeServiceCall ( "/order/list", $params, RequestMethod::GET, $requestOptions );
        return new OrderList ( $response );
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Order
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function refund($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/refund", $params, RequestMethod::POST, $requestOptions );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    
    /**
     * Restructuring the order response.
     *
     * @param array $response
     *
     * @return array
     */
    private static function updateOrderResponseStructure($response) {
        if (array_key_exists ( "card", $response )) {
            $card = $response ["card"];
            $card ["card_exp_month"] = $card ["expiry_month"];
            $card ["card_exp_year"] = $card ["expiry_year"];
            unset ( $card ["expiry_month"] );
            unset ( $card ["expiry_year"] );
        }
        return $response;
    }
}

