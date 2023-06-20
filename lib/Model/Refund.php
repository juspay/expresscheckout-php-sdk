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
    
    private static $result = [];

    public function __get($name) {
        return self::$result[$name];
    }
    /**
     * Constructor
     *
     * @param array $params
     */
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

