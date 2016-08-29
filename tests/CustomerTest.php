<?php

namespace Juspay\Test;

use Juspay\Model\Customer;

class CustomerTest extends \PHPUnit_Framework_TestCase {
    public $customer;
    public function testCreate() {
        $customerId = uniqid ();
        $params = array ();
        $params ['first_name'] = "Juspay";
        $params ['last_name'] = "Technologies";
        $params ['mobile_country_code'] = "91";
        $params ['mobile_number'] = "9988776655";
        $params ['email_address'] = "support@juspay.in";
        $params ['object_reference_id'] = $customerId;
        $customer = Customer::create ( $params );
        assert ( $customer != null );
        assert ( $customer->id != null );
        assert ( $customer->firstName != null );
        assert ( $customer->lastName != null );
        assert ( $customer->mobileCountryCode != null );
        assert ( $customer->mobileNumber != null );
        assert ( $customer->emailAddress != null );
        assert ( $customer->objectReferenceId != null );
        $this->customer = $customer;
    }
    public function testUpdate() {
        $this->testCreate ();
        $params = array ();
        $params ['first_name'] = "Juspay1";
        $params ['last_name'] = "Technologies1";
        $params ['mobile_country_code'] = "92";
        $params ['mobile_number'] = "9988776656";
        $params ['email_address'] = "support1@juspay.in";
        $customer = Customer::update ( $this->customer->id, $params );
        assert ( $customer != null );
        assert ( $customer->id != null );
        assert ( $customer->firstName == $params ['first_name'] );
        assert ( $customer->lastName == $params ['last_name'] );
        assert ( $customer->mobileCountryCode == $params ['mobile_country_code'] );
        assert ( $customer->mobileNumber == $params ['mobile_number'] );
        assert ( $customer->emailAddress == $params ['email_address'] );
    }
    public function testList() {
        $this->testCreate ();
        $customers = Customer::listAll ( null );
        assert ( $customers != null );
        assert ( count ( $customers ) > 0 );
    }
    public function testGet() {
        $this->testCreate ();
        $customer = Customer::get ( $this->customer->id );
        assert ( $customer != null );
    }
}
require_once __DIR__ . '/TestEnvironment.php';
