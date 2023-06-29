<?php
namespace Juspay\Model;

class JuspayJWT extends IJuspayJWT {

    public function __construct(array $keys,  string $publicKeyKid, string $privateKeyKid) {
        $this->keys = $keys;
        $this->publicKeyKid = $publicKeyKid;
        $this->privateKeyKid = $privateKeyKid;
    }
    public $privateKeyKid;
    public $publicKeyKid;
    public function preparePayload(string $payload) : string {
        $signedPayload = $this->Sign->sign($this->keys["privateKey"], $payload);
        $signedPayload = explode(".", $signedPayload);
        $signedPayload = "{\"header\":\"{$signedPayload[0]}\",\"payload\":\"{$signedPayload[1]}\",\"signature\":\"{$signedPayload[2]}\"}";
        $encryptedPayload = $this->Enc->encrypt($this->keys["publicKey"], $signedPayload);
        $encryptedPayload = explode(".", $encryptedPayload);
        $encryptedPayload = "{\"header\":\"{$encryptedPayload[0]}\",\"encryptedKey\": \"{$encryptedPayload[1]}\",\"iv\":\"{$encryptedPayload[2]}\",\"encryptedPayload\":\"{$encryptedPayload[3]}\",\"tag\":\"{$encryptedPayload[4]}\"}";
        return $encryptedPayload;
    }

    public function consumePayload(string $encryptedPayload) : string {
        $encryptedPayload = json_decode($encryptedPayload, true);
        $signedPayload = $this->Enc->decrypt($this->keys["privateKey"], "{$encryptedPayload["header"]}.{$encryptedPayload["encryptedKey"]}.{$encryptedPayload["iv"]}.{$encryptedPayload["encryptedPayload"]}.{$encryptedPayload["tag"]}");
        return $this->Sign->verifySign($this->keys["publicKey"], "{$signedPayload["header"]}.{$signedPayload["payload"]}.{$signedPayload["signature"]}");
    }

    public function Initialize() : void { // Factory Method
        $this->Sign = new SignRSA($this->publicKeyKid);
        $this->Enc = new EncRSAOEAP($this->privateKeyKid);
    }
}
?>