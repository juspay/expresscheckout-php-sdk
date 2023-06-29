<?php
namespace Juspay\Model;

abstract class IJuspayJWT {
    public $keys;


    abstract public function preparePayload(string $payload) : string;

    abstract public function consumePayload(string $encPaylaod) : string;

    abstract public function Initialize() : void;
    public $Sign;

    public $Enc;

}
?>