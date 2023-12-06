<?php
namespace Juspay\JWT;
abstract class ISigningAlgorithm {
    /**
     * Signer
     * @param string $payload
     * @param JWK $key
     * @return string
     */
    abstract public function sign($payload, $key);

    /**
     * Verfiy the Signature
     * @param string $payload
     * @param string $signature
     * @param JWK $key
     */
    abstract public function verifySign($payload, $signature, $key);

    /**
     * @return string
     */
    abstract public function getAlgorithmName();
} 
?>