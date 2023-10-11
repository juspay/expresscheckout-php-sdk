<?php
namespace Juspay\Model;

class JuspayJWT extends IJuspayJWT {

    /**
     * Prepare the payload.
     *
     * @param array $keys Private and Public key array ["publicKey"] ["privateKey"]
     * @param string $keyId Public Key id
     */
    public function __construct(array $keys, $keyId) {
        $this->keys = $keys;
        $this->keyId = $keyId;
    }
    public $keyId;

    /**
     * Prepare the payload.
     *
     * @param string $payload The payload to prepare.
     * @return string The prepared payload.
     */
    public function preparePayload($payload) {
        $signedPayload = $this->Sign->sign($this->keys["privateKey"], $payload);
        $signedPayload = explode(".", $signedPayload);
        $signedPayload = "{\"header\":\"{$signedPayload[0]}\",\"payload\":\"{$signedPayload[1]}\",\"signature\":\"{$signedPayload[2]}\"}";
        $encryptedPayload = $this->Enc->encrypt($this->keys["publicKey"], $signedPayload);
        $encryptedPayload = explode(".", $encryptedPayload);
        $encryptedPayload = "{\"header\":\"{$encryptedPayload[0]}\",\"encryptedKey\": \"{$encryptedPayload[1]}\",\"iv\":\"{$encryptedPayload[2]}\",\"encryptedPayload\":\"{$encryptedPayload[3]}\",\"tag\":\"{$encryptedPayload[4]}\"}";
        return $encryptedPayload;
    }

    /**
     * Consume payload
     * @param string $encryptedPayload Encrypted payload
     * @return string Returns the decrypted string
     */

    public function consumePayload($encryptedPayload) {
        $encryptedPayload = json_decode($encryptedPayload, true);
        $signedPayload = json_decode($this->Enc->decrypt($this->keys["privateKey"], "{$encryptedPayload["header"]}.{$encryptedPayload["encryptedKey"]}.{$encryptedPayload["iv"]}.{$encryptedPayload["encryptedPayload"]}.{$encryptedPayload["tag"]}"), true);
        return $this->Sign->verifySign($this->keys["publicKey"], "{$signedPayload["header"]}.{$signedPayload["payload"]}.{$signedPayload["signature"]}");
    }

    /**
     * Initialize Signer and Encrypter
     * @return void
     */
    public function Initialize() { // Factory Method
        if (version_compare(phpversion(), "7.1.0", ">=")) {
            $this->Sign = new SignRSA($this->keyId);
            $this->Enc = new EncRSAOEAP($this->keyId);
        } else {
            $this->Sign = new SignRSA5($this->keyId);
            $this->Enc = new EncRSAOEAP5($this->keyId);
        }
    }
}
?>