<?php

namespace Juspay\Model;

/**
 * Class Refund
 *
 * @property string $id
 * @property string $uniqueRequestId
 * @property string $ref
 * @property float $amount
 * @property DateTime $created
 * @property string $status
 *
 * @package Juspay\Model
 */
class Refund extends JuspayEntity {
    
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

