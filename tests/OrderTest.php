<?php

namespace Juspay\Test;

use Juspay\Model\Order;
use Juspay\Model\OrderList;
use Juspay\Exception\JuspayException;

class OrderTest extends \PHPUnit_Framework_TestCase {
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
        assert ( $order != null );
        assert ( $order->id != null );
        assert ( $order->status == "CREATED" );
        assert ( $order->statusId == 1 );
        assert ($order->paymentLinks != null);
        assert ($order->paymentLinks->web != null);
        assert ($order->paymentLinks->mobile != null);
        assert ($order->paymentLinks->iframe != null);
        $this->order = $order;
    }
    public function testStatus() {
        $this->testCreate ();
        $params = array ();
        $params ['order_id'] = $this->order->orderId;
        $order = Order::status ( $params );
        assert ( $order != null );
        assert ( $this->order->orderId == $order->orderId );
    }
    public function testList() {
        $this->testCreate ();
        $orderList = Order::listAll ( null );
        assert ( $orderList != null );
        assert ( count ( $orderList->list ) != 0 );
    }
    public function testUpdate() {
        $this->testCreate ();
        $params = array ();
        $params ['order_id'] = $this->order->orderId;
        $params ['amount'] = $this->order->amount + 100;
        $order = Order::update ( $params );
        assert ( $order != null );
        assert ( $order->amount == $params ['amount'] );
    }
    public function testRefund() {
        $this->testCreate ();
        $params = array ();
        $params ['order_id'] = $this->order->orderId;
        $params ['amount'] = 10;
        try {
            // Testing refund needs a successful order.
            // Here we are testing only unsuccessful orders using catch.
            $order = Order::refund ( $params );
            assert ( order != null );
            assert ( $this->order->orderId == $order->orderId );
            assert ( $order->refunded == true );
        } catch ( JuspayException $e ) {
            assert ( "invalid.order.not_successful" == $e->getErrorCode () );
        }
    }
}
require_once __DIR__ . '/TestEnvironment.php';