<?php
namespace Juspay\JWT;
abstract class IKeyEncryption {
    abstract public function encryptKey(JWk $key, $cek);

    abstract public function decryptKey(JWK $key, $encryptedKey);

    abstract public function getAlgorithmName();
}
?>