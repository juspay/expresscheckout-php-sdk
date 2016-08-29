<?php

namespace Juspay\Model;

use Juspay\RequestMethod;
use Juspay\Exception\InvalidRequestException;

class Wallet extends JuspayEntity {
    public $id;
    public $object;
    public $wallet;
    public $token;
    public $currentBalance;
    public $lastRefreshed;
    public function __construct($params) {
        foreach ( array_keys ( $params ) as $key ) {
            $newKey = $this->camelize ( $key );
            if ($newKey == "lastRefreshed") {
                $this->$newKey = date_create ( $params [$key] );
            } else {
                $this->$newKey = $params [$key];
            }
        }
    }
    public static function listAll($customerId, $requestOptions = null) {
        if ($customerId == null || $customerId == "") {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets", null, RequestMethod::GET, $requestOptions );
        return new WalletList ( $response );
    }
    public static function refresh($customerId, $requestOptions = null) {
        if ($customerId == null || $customerId == "") {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets/refresh-balances", null, RequestMethod::GET, $requestOptions );
        return new WalletList ( $response );
    }
}
