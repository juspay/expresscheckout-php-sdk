<?php
namespace Juspay\Model;
use Juspay\JWT\JWK;
use Juspay\JWT\JWS;
use Juspay\JWT\RSA256;


class SignRSA extends ISign {

    public $kid;
    
    /**
    * 
    * @param string $kid private key key id
    */
    public function __construct($kid) {
        $this->kid = $kid;
    }

    /**
    * 
    * @param string $privateKey Key used to sing
    * @param string $payload Payload to be signed
    * @return string Returns signed string
    */
    public function sign($privateKey, $payload) {
        $privateJWKKey = new JWK($privateKey);
        $jws = new JWS(new RSA256());
        return $jws->createJWSandSerialize($privateJWKKey, $payload, ['kid' => $this->kid]);
    }

    /**
     * Verify the Signature and return decoded payload
     * @param string $publicKey Key used to verify the signature
     * @param string $signedPayload Payload to be verified and decoded
     * @return string Decoded payload
     */
    public function verifySign($publicKey, $signedPayload) {
        $publicJWKKey = new JWK($publicKey);
        $jws = new JWS(new RSA256());
        $jws->verify($publicJWKKey, $signedPayload);
        return $jws->payload;
    }
}
?>