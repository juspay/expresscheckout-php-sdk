<?php

namespace Juspay\Test;

use Juspay\Model\Session;


class SessionTest extends TestCase {
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
        $this->assertTrue($session->status == "NEW");
        $this->assertTrue($session->id != null);
        $this->assertTrue($session->orderId != null);
        $this->assertTrue($session->paymentLinks != null);
        $this->assertTrue($session->sdkPayload != null);
        $this->session = $session;
    }
}
require_once __DIR__ . '/TestEnvironment.php';
