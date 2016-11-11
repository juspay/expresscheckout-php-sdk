<?php

namespace Juspay\Test;

use Juspay\JuspayEnvironment;

class TestEnvironment {
    public static $merchantId = "ankur_juspay";
    public static $apiKey = "6080EF68BEB5469FAE7E0E07FC384D49";
    public static $baseUrl = JuspayEnvironment::SANDBOX_BASE_URL;
}
JuspayEnvironment::init ()
->withApiKey ( TestEnvironment::$apiKey )
->withBaseUrl ( TestEnvironment::$baseUrl );