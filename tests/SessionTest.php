<?php

namespace Juspay\Test;

use Juspay\Model\Session;


class SessionTest extends \PHPUnit_Framework_TestCase {
    public $session;
    public function testCreate() {

        $orderTest = new OrderTest();
        $orderTest->testCreate();
        $orderId = $orderTest->order->orderId;
        $customerTest = new CustomerTest();
        $customerTest->testCreate();
        $customerId = $customerTest->customer->objectReferenceId;
        $params = json_decode("{\n\"amount\":\"10.00\",\n\"order_id\":\"$orderId\",\n\"customer_id\":\"$customerId\",\n\"payment_page_client_id\":\"azharamin\",\n\"action\":\"paymentPage\",\n\"return_url\": \"https://google.com\"\n}", true);
        $session = Session::create ( $params );
        assert($session->status == "NEW");
        assert($session->id != null);
        assert($session->orderId != null);
        assert($session->paymentLinks != null);
        assert($session->sdkPayload != null);
        $this->session = $session;
    }
}
require_once __DIR__ . '/TestEnvironment.php';
