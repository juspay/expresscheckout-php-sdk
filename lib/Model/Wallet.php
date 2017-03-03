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
 * @property boolean $linked
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
    
    /**
     *
     * @param string $customerId
     * @param string $gateway
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function create($customerId, $gateway, $requestOptions = null) {
        if ($customerId == null || $customerId == "" || $gateway == null || $gateway == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['gateway'] = $gateway;
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets", $params, RequestMethod::POST, $requestOptions );
        return new Wallet ( $response );
    }
    
    /**
     *
     * @param string $customerId
     * @param string $gateway
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function createAndAuthenticate($customerId, $gateway, $requestOptions = null) {
        if ($customerId == null || $customerId == "" || $gateway == null || $gateway == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['gateway'] = $gateway;
        $params ['command'] = 'authenticate';
        $response = self::makeServiceCall ( "/customers/" . $customerId . "/wallets", $params, RequestMethod::POST, $requestOptions );
        return new Wallet ( $response );
    }
    
    /**
     *
     * @param string $walletId
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function refreshByWalletId($walletId, $requestOptions = null) {
        if ($walletId == null || $walletId == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['command'] = 'refresh';
        $response = self::makeServiceCall ( "/wallets/" . $walletId, $params, RequestMethod::GET, $requestOptions );
        return new Wallet ( $response );
    }
    
    /**
     *
     * @param string $walletId
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function authenticate($walletId, $requestOptions = null) {
        if ($walletId == null || $walletId == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['command'] = 'authenticate';
        $response = self::makeServiceCall ( "/wallets/" . $walletId, $params, RequestMethod::POST, $requestOptions );
        return new Wallet ( $response );
    }
    
    /**
     *
     * @param string $walletId
     * @param string $otp
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function link($walletId, $otp, $requestOptions = null) {
        if ($walletId == null || $walletId == "" || $otp == null || $otp == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['command'] = 'link';
        $params ['otp'] = $otp;
        $response = self::makeServiceCall ( "/wallets/" . $walletId, $params, RequestMethod::POST, $requestOptions );
        return new Wallet ( $response );
    }
    
    /**
     *
     * @param string $walletId
     * @param RequestOptions|null $requestOptions
     *
     * @return Wallet
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    public static function delink($walletId, $requestOptions = null) {
        if ($walletId == null || $walletId == "") {
            throw new InvalidRequestException ();
        }
        $params = array ();
        $params ['command'] = 'delink';
        $response = self::makeServiceCall ( "/wallets/" . $walletId, $params, RequestMethod::POST, $requestOptions );
        return new Wallet ( $response );
    }    
}
