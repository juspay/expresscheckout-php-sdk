<?php

namespace Juspay\Test;

use Juspay\Model\Customer;
use Juspay\Model\Wallet;

class WalletTest extends \PHPUnit_Framework_TestCase {
    private $customerTest;
    public function testList() {
        $this->customerTest = new CustomerTest ();
        $this->customerTest->testCreate ();
        $wallets = Wallet::listAll ( $this->customerTest->customer->objectReferenceId );
        assert ( $wallets != null );
    }
    public function testRefresh() {
        $this->customerTest = new CustomerTest ();
        $this->customerTest->testCreate ();
        $wallets = Wallet::refresh ( $this->customerTest->customer->objectReferenceId );
        assert ( $wallets != null );
    }
}
require_once __DIR__ . '/../init.php';