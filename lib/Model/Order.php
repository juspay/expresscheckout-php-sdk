<?php

namespace Juspay\Model;

use Juspay\RequestMethod;
use Juspay\Exception\InvalidRequestException;

class Order extends JuspayEntity {
    public $id;
    public $orderId;
    public $merchantId;
    public $txnId;
    public $amount;
    public $currency;
    public $customerId;
    public $customerEmail;
    public $customerPhone;
    public $description;
    public $productId;
    public $gatewayId;
    public $returnUrl;
    public $udf1;
    public $udf2;
    public $udf3;
    public $udf4;
    public $udf5;
    public $udf6;
    public $udf7;
    public $udf8;
    public $udf9;
    public $udf10;
    public $status;
    public $statusId;
    public $refunded;
    public $amountRefunded;
    public $refunds;
    public $bankErrorCode;
    public $bankErrorMessage;
    public $card;
    public $paymentGatewayResponse;
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
            } else {
                $this->$newKey = $params [$key];
            }
        }
    }
    public static function create($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/create", $params, RequestMethod::POST, $requestOptions );
        $response = self::addInputParamsToResponse ( $params, $response );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    public static function status($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/status", $params, RequestMethod::POST, $requestOptions );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    public static function update($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/update", $params, RequestMethod::POST, $requestOptions );
        return new Order ( $response );
    }
    public static function listAll($params, $requestOptions = null) {
        $response = self::makeServiceCall ( "/order/list", $params, RequestMethod::GET, $requestOptions );
        return new OrderList ( $response );
    }
    public static function refund($params, $requestOptions = null) {
        if ($params == null || count ( $params ) == 0) {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/order/refund", $params, RequestMethod::POST, $requestOptions );
        $response = self::updateOrderResponseStructure ( $response );
        return new Order ( $response );
    }
    
    // Restructuring the order response.
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

