<?php
namespace Juspay\Model;

abstract class IJuspayJWT {
    public $keys;

    /**
     * Prepare the payload.
     *
     * @param string $payload The payload to prepare.
     * @return string The prepared payload.
     */
    abstract public function preparePayload($payload);
    /**
     * Consume payload
     * @param string $encPaylaod Encrypted payload
     * @return string Returns the decrypted string
     */
    abstract public function consumePayload($encPaylaod);

    /**
     * Initialize Signer and Encrypter
     * @return void
     */
    abstract public function Initialize();
    public $Sign;

    public $Enc;

}
?>