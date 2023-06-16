<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\RequestMethod;
use Juspay\RequestOptions;


class Session extends JuspayEntity {
    
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params) {
        $params = $this->camelizeArrayKeysRecursive($params);
        foreach( array_keys($params) as $key) {
            $this->$key = $params[$key];
        }
    }
    
    /**
     *
     * @param array $params
     * @param RequestOptions|null $requestOptions
     *
     * @return Session
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
        $response = self::makeServiceCall ( "/session", $params, RequestMethod::POST, $requestOptions, "application/json");
        return new Session ( $response );
    }
}
