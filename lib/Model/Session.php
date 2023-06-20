<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\RequestMethod;
use Juspay\RequestOptions;


class Session extends JuspayResponse {
    
    
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
