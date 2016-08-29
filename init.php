<?php
use Juspay\JuspayEnvironment;
use Juspay\Test\TestEnvironment;

require __DIR__ . '/vendor/autoload.php';

JuspayEnvironment::init ()
->withApiKey ( TestEnvironment::$apiKey )
->withBaseUrl ( TestEnvironment::$baseUrl );