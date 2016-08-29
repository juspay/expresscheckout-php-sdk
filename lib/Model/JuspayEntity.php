<?php

namespace Juspay\Model;

use Juspay\Exception\APIConnectionException;
use Juspay\Exception\APIException;
use Juspay\Exception\AuthenticationException;
use Juspay\Exception\InvalidRequestException;
use Juspay\JuspayEnvironment;
use Juspay\RequestMethod;
use Juspay\RequestOptions;

/**
 * Class JuspayEntity
 *
 * @package Juspay\Model
 */
abstract class JuspayEntity {
    
    /**
     *
     * @param string $path
     * @param array|null $params
     * @param string $method
     * @param RequestOptions|null $requestOptions
     *
     * @return array
     *
     * @throws APIConnectionException
     * @throws APIException
     * @throws AuthenticationException
     * @throws InvalidRequestException
     */
    protected static function makeServiceCall($path, $params, $method, $requestOptions) {
        if ($requestOptions == null) {
            $requestOptions = RequestOptions::createDefault ();
        }
        $url = JuspayEnvironment::getBaseUrl () . $path;
        $curlObject = curl_init ();
        curl_setopt ( $curlObject, CURLOPT_RETURNTRANSFER, true );
        curl_setopt ( $curlObject, CURLOPT_HEADER, true );
        curl_setopt ( $curlObject, CURLOPT_NOBODY, false );
        curl_setopt ( $curlObject, CURLOPT_USERPWD, JuspayEnvironment::getApiKey () );
        curl_setopt ( $curlObject, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
        curl_setopt ( $curlObject, CURLOPT_USERAGENT, JuspayEnvironment::getSdkVersion () );
        curl_setopt ( $curlObject, CURLOPT_TIMEOUT, JuspayEnvironment::getReadTimeout () );
        curl_setopt ( $curlObject, CURLOPT_CONNECTTIMEOUT, JuspayEnvironment::getConnectTimeout () );
        curl_setopt ( $curlObject, CURLOPT_HTTPHEADER, array (
                'version: ' . JuspayEnvironment::getApiVersion () 
        ) );
        if ($method == RequestMethod::GET) {
            curl_setopt ( $curlObject, CURLOPT_HTTPGET, 1 );
            if ($params != null) {
                $encodedParams = http_build_query ( $params );
                if ($encodedParams != null && $encodedParams != "") {
                    $url = $url . "?" . $encodedParams;
                }
            }
        } else {
            curl_setopt ( $curlObject, CURLOPT_POST, 1 );
            if ($params == null) {
                curl_setopt ( $curlObject, CURLOPT_POSTFIELDSIZE, 0 );
            } else {
                curl_setopt ( $curlObject, CURLOPT_POSTFIELDS, $params );
            }
        }
        curl_setopt ( $curlObject, CURLOPT_URL, $url );
        $response = curl_exec ( $curlObject );
        if ($response == false) {
            throw new APIConnectionException ( - 1, "connection_error", "connection_error", curl_error ( $curlObject ) );
        } else {
            $responseCode = curl_getinfo ( $curlObject, CURLINFO_HTTP_CODE );
            $headerSize = curl_getinfo ( $curlObject, CURLINFO_HEADER_SIZE );
            $responseBody = json_decode ( substr ( $response, $headerSize ), true );
            curl_close ( $curlObject );
            if ($responseCode >= 200 && $responseCode < 300) {
                return $responseBody;
            } else {
                $status = null;
                $errorCode = null;
                $errorMessage = null;
                if ($responseBody != null) {
                    if (array_key_exists ( "status", $responseBody ) != null) {
                        $status = $responseBody ['status'];
                    }
                    if (array_key_exists ( "error_code", $responseBody ) != null) {
                        $errorCode = $responseBody ['error_code'];
                    }
                    if (array_key_exists ( "error_message", $responseBody ) != null) {
                        $errorMessage = $responseBody ['error_message'];
                    }
                }
                switch ($responseCode) {
                    case 400 :
                    case 404 :
                        throw new InvalidRequestException ( $responseCode, $status, $errorCode, $errorMessage );
                    case 401 :
                        throw new AuthenticationException ( $responseCode, $status, $errorCode, $errorMessage );
                    default :
                        throw new APIException ( $responseCode, "internal_error", "internal_error", "Something went wrong." );
                }
            }
        }
    }
    
    /**
     *
     * @param string $input
     * @param string|null $separator
     *
     * @return string
     */
    protected function camelize($input, $separator = '_') {
        $output = str_replace ( $separator, '', ucwords ( $input, $separator ) );
        $output [0] = strtolower ( $output [0] );
        return $output;
    }
    
    /**
     *
     * @param array $params
     * @param array $response
     *
     * @return array
     */
    protected static function addInputParamsToResponse($params, $response) {
        foreach ( array_keys ( $params ) as $key ) {
            $response [$key] = $params [$key];
        }
        return $response;
    }
}
