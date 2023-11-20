<?php

namespace Juspay\Exception;

use Exception;
use Juspay\JuspayEnvironment;

class JuspayException extends Exception {
    private $httpResponseCode;
    private $status;
    private $errorCode;
    private $errorMessage;
    public function __construct($httpResponseCode, $status, $errorCode, $errorMessage) {
        parent::__construct ( $errorMessage );
        $this->httpResponseCode = $httpResponseCode;
        $this->status = $status;
        $this->addErrorCode = $errorCode;
        $this->addErrorMessage = $errorMessage;
        JuspayEnvironment::$logger->addError(json_encode(["status_code" => $httpResponseCode, "status" => $status, "error_code" => $errorCode, "errorMessage"=> $errorMessage]));
    }
    public function getHttpResponseCode() {
        return $this->httpResponseCode;
    }
    public function getStatus() {
        return $this->status;
    }
    public function getErrorCode() {
        return $this->addErrorCode;
    }
    public function getErrorMessage() {
        return $this->addErrorMessage;
    }
}
