<?php

namespace Juspay\Test;

use Juspay\Model\Order;
use Juspay\Model\OrderList;
use Juspay\Exception\JuspayException;

class OrderTest extends TestCase {
    public $order;
    public function testCreate() {
        $orderId = uniqid ();
        $params = array ();
        $params ['order_id'] = $orderId;
        $params ['amount'] = 10000.0;
        $params ['currency'] = "INR";
        $params ['customer_id'] = "juspay_test_1";
        $params ['customer_email'] = "test@juspay.in";
        $params ['customer_phone'] = "9988776655";
        $params ['product_id'] = "123456";
        $params ['return_url'] = "https://abc.xyz.com/123456";
        $params ['description'] = "Sample Description";
        $params ['billing_address_first_name'] = "Juspay";
        $params ['billing_address_last_name'] = "Technologies";
        $params ['billing_address_line1'] = "Girija Building";
        $params ['billing_address_line2'] = "Ganapathi Temple Road";
        $params ['billing_address_line3'] = "8th Block, Koramangala";
        $params ['billing_address_city'] = "Bengaluru";
        $params ['billing_address_state'] = "Karnataka";
        $params ['billing_address_country'] = "India";
        $params ['billing_address_postal_code'] = "560095";
        $params ['billing_address_phone'] = "9988776655";
        $params ['billing_address_country_code_iso'] = "IND";
        $params ['shipping_address_first_name'] = "Juspay";
        $params ['shipping_address_last_name'] = "Technologies";
        $params ['shipping_address_line1'] = "Girija Building";
        $params ['shipping_address_line2'] = "Ganapathi Temple Road";
        $params ['shipping_address_line3'] = "8th Block, Koramangala";
        $params ['shipping_address_city'] = "Bengaluru";
        $params ['shipping_address_state'] = "Karnataka";
        $params ['shipping_address_country'] = "India";
        $params ['shipping_address_postal_code'] = "560095";
        $params ['shipping_address_phone'] = "9988776655";
        $params ['shipping_address_country_code_iso'] = "IND";
        $order = Order::create ( $params );
        $this->assertTrue ( $order != null );
        $this->assertTrue ( $order->id != null );
        $this->assertTrue ( $order->status == "CREATED" );
        $this->assertTrue ( $order->statusId == 1 );
        $this->assertTrue ($order->paymentLinks != null);
        $this->assertTrue ($order->paymentLinks["web"] != null);
        $this->assertTrue ($order->paymentLinks["mobile"] != null);
        $this->assertTrue ($order->paymentLinks["iframe"] != null);
        $this->order = $order;
    }
    public function testStatus() {
        $this->testCreate ();
        $params = array ();
        $params ['order_id'] = $this->order->orderId;
        $order = Order::status ( $params );
        $this->assertTrue( $order != null );
        $this->assertTrue( $this->order->orderId == $order->orderId );
    }
    // public function testList() {
    //     $this->testCreate ();
    //     $orderList = Order::listAll ( null );
    //     $this->assertTrue ( $orderList != null );
    //     $this->assertTrue ( count ( $orderList->list ) != 0 );
    // }
    public function testUpdate() {
        $this->testCreate ();
        $params = array ();
        $orderId = $this->order->orderId;
        $params ['amount'] = $this->order->amount + 100;
        $order = Order::update ( $params, $orderId );
        $this->assertTrue ( $order != null );
        $this->assertTrue ( $order->amount == $params ['amount'] );
    }
    public function testRefund() {
        $this->testCreate ();
        $params = array ();
        $params ['order_id'] = $this->order->orderId;
        $params ['amount'] = 10;
        $params['unique_request_id'] = uniqid('php_sdk_test_');
        try {
            // Testing refund needs a successful order.
            // Here we are testing only unsuccessful orders using catch.
            $order = Order::refund ( $params );
            $this->assertTrue ( $order != null );
            $this->assertTrue ( $this->order->orderId == $order->orderId );
            $this->assertTrue ( $order->refunded == true );
        } catch ( JuspayException $e ) {
            $this->assertTrue ( "invalid.order.not_successful" == $e->getErrorCode () );
        }
    }
}
require_once __DIR__ . '/TestEnvironment.php';