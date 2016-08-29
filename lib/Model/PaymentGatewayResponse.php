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
    public function __construct($params) {
        foreach ( array_keys ( $params ) as $key ) {
            $newKey = $this->camelize ( $key );
            if ($newKey == "created") {
                $this->$newKey = date_create ( $params [$key] );
            } else {
                $this->$newKey = $params [$key];
            }
        }
    }
}

