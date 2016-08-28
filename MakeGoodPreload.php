<?php

use Juspay\JuspayEnvironment;

// This is a preload script to be used with
// the Eclipse makegood continuous integration plugin
// see https://github.com/piece/makegood/releases
error_reporting(E_ALL);
$loader = require 'vendor/autoload.php';

require_once __DIR__.'/lib/JuspayEnvironment.php';

JuspayEnvironment::init()
->withApiKey("6080EF68BEB5469FAE7E0E07FC384D49")
->withBaseUrl(JuspayEnvironment::SANDBOX_BASE_URL);
