<?php

namespace Juspay\Test;

use Juspay\Model\PaymentMethod;

class PaymentMethodTest extends \PHPUnit_Framework_TestCase {
    public function testList() {
        $paymentMethods = PaymentMethod::listAll( TestEnvironment::$merchantId );
        assert ( $paymentMethods != null );
    }
}
require_once __DIR__ . '/TestEnvironment.php';