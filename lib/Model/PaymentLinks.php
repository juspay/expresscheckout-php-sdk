<?php

namespace Juspay\Model;

/**
 * Class PaymentLinks
 *
 * @property string $web
 * @property string $mobile
 * @property string $iframe
 *
 * @package Juspay\Model
 */
class PaymentLinks extends JuspayEntity {

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
             self::$result[$newKey] = $params [$key];
        }
    }
}

