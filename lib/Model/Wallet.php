<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\RequestMethod;

/**
 * Class Wallet
 *
 * @property string $id
 * @property string $object
 * @property string $wallet
 * @property string $token
 * @property float $currentBalance
 * @property DateTime $lastRefreshed
 *
 * @package Juspay\Model
 */
class Wallet extends JuspayEntity {
    
    /**
     * Constructor
     *
     * @param array $params
     */
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
    
    /**
     *
     * @param string $customerId
     * @param RequestOptions|null $requestOptions
     *
     * @return WalletList
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function listAll($customerId, $requestOptions = null) {
        if ($customerId == null || $customerId == "") {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets", null, RequestMethod::GET, $requestOptions );
        return new WalletList ( $response );
    }
    
    /**
     *
     * @param string $customerId
     * @param RequestOptions|null $requestOptions
     *
     * @return WalletList
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function refresh($customerId, $requestOptions = null) {
        if ($customerId == null || $customerId == "") {
            throw new InvalidRequestException ();
        }
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets/refresh-balances", null, RequestMethod::GET, $requestOptions );
        return new WalletList ( $response );
    }
}
