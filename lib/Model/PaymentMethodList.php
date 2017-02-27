<?php

namespace Juspay\Model;

/**
 * Class PaymentMethodList
 *
 * @package Juspay\Model
 */
class PaymentMethodList extends JuspayEntityList {
    
    /**
     * Constructor
     *
     * @param array $params
     */
    public function __construct($params) {
        parent::__construct ( $params );
        for($i = 0; $i < sizeof ( $params ["payment_methods"] ); $i ++) {
            $this->list [$i] = new PaymentMethod ( $params ["payment_methods"] [$i] );
        }
    }
}
