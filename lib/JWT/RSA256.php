<?php
namespace Juspay\JWT;
use Juspay\Exception\JuspayException;
class RSA256 extends ISigningAlgorithm {

    /**
     * @param string $payload
     * @param JWK $privateKey
     */
    public function sign($payload, $privateKey) {
        $privateKey = new RSAKey($privateKey->jsonSerialize());
        $result = openssl_sign($payload, $signature, $privateKey->toPEM(), $this->getAlgorithm());
        if ($result === false) {
            throw new JuspayException(-1, "ERROR", "jws_error", "'An error occurred during the creation of the signature");
        }
        return $signature;
        
    }

     /**
     * @param string $encodedPayload
     * @param string $signature
     * @param JWK $publicKey
     */
    public function verifySign($encodedPayload, $signature, $publicKey) {
        $publicKey = new RSAKey($publicKey->jsonSerialize());
        $res = openssl_verify($encodedPayload, $signature, $publicKey->toPEM(), $this->getAlgorithm());
        return $res === 1;
    }

    /**
     * @return string
     */
    protected function getAlgorithm()
    {
        return 'sha256';
    }

    /**
     * @return string
     */
    public function getAlgorithmName()
    {
        return 'RS256';
    }
}
?>