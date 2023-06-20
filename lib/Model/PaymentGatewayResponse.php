<?php

namespace Juspay\Model;

/**
 * Class PaymentGatewayResponse
 *
 * @property string $rrn
 * @property string $epgTxnId
 * @property string $authIdCode
 * @property string $txnId
 * @property string $respCode
 * @property string $respMessage
 * @property DateTime $created
 *
 * @package Juspay\Model
 */
class PaymentGatewayResponse extends JuspayEntity {
    
    /**
     * Constructor
     *
     * @param array $params
     */

    private static $result = [];

    public function __get($name) {
        return self::$result[$name];
    }
    public function __construct($params) {
        foreach ( array_keys ( $params ) as $key ) {
            $newKey = $this->camelize ( $key );
            if ($newKey == "created") {
                 self::$result[$newKey] = date_create ( $params [$key] );
            } else {
                 self::$result[$newKey] = $params [$key];
            }
        }
    }
}

