<?php

namespace Juspay\Model;

class Refund extends JuspayEntity {
    public $id;
    public $uniqueRequestId;
    public $ref;
    public $amount;
    public $created;
    public $status;
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

