<?php
namespace Juspay\Model;

use Jose\Factory\JWEFactory;
use Jose\Factory\JWKFactory;
use Jose\Loader;
use Juspay\JWT\AES256GCM;
use Juspay\JWT\JWE;
use Juspay\JWT\JWK;
use Juspay\JWT\RSAOAEP256;

class EncRSAOEAP5 extends IEnc {

    public $kid;
    /**
    * 
    * @param string $kid private key key id
    */
    public function __construct($kid) {
        $this->kid = $kid;
    }
     /**
     * Encrypt the payload
     * @param string $publicKey Key used to encrypt the payload/encrypt the encryption key
     * @param string $payload Payload to be encrypted
     * @return string Encrypted payload
     */
    public function encrypt($publicKey, $payload) {
        $publicJWKKey = new JWK($publicKey);
        $jwe = new JWE(new RSAOAEP256(), new AES256GCM());
        return $jwe->createJWEAndSerialize($publicJWKKey, $payload, ['kid' => $this->kid]);    
    }
     /**
     * Decrypt the encrypted payload
     * @param string $privateKey Key used to decrypt the payload/decrypt the encryption key
     * @param string $encryptedPayload Payload to be decrypted
     * @return string Encrypted payload
     */
    public function decrypt($privateKey, $encryptedPayload) {
        $privateJWKKey = new JWK($privateKey);
        $jwe = new JWE(new RSAOAEP256(), new AES256GCM());
        return $jwe->decryptJWE($privateJWKKey, $encryptedPayload);
    }
}
?>