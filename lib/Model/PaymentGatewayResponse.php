<?php

namespace Juspay\Model;

class PaymentGatewayResponse extends JuspayEntity {
    public $rrn;
    public $epgTxnId;
    public $authIdCode;
    public $txnId;
    public $respCode;
    public $respMessage;
    public $created;
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

