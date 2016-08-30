<?php

namespace Juspay\Test;

use Juspay\JuspayEnvironment;

class TestEnvironment {
    public static $merchantId = "";
    public static $apiKey = "";
    public static $baseUrl = JuspayEnvironment::SANDBOX_BASE_URL;
}
JuspayEnvironment::init ()
->withApiKey ( TestEnvironment::$apiKey )
->withBaseUrl ( TestEnvironment::$baseUrl );