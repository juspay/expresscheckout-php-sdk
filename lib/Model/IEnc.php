<?php

namespace Juspay\Model;
 abstract class IEnc {
    abstract public function encrypt(string $key, string $plainText) : string;
    abstract public function decrypt(string $key, string $encryptedPayload) : string;
 }
?>