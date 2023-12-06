<?php
namespace Juspay\JWT;
use phpseclib3\Crypt\PublicKeyLoader;
class RSAOAEP256 extends IKeyEncryption {
    public function encryptKey(JWk $key, $cek) {
        $rsaKey = new RSAKey($key->jsonSerialize());
        $rsa = PublicKeyLoader::load($rsaKey->toPEM())->withHash('sha256')->withMGFHash('sha256');
        $enc = $rsa->encrypt($cek);
        return $enc;
    }

    public function decryptKey(JWK $key, $encryptedKey) {
        $rsaKey = new RSAKey($key->jsonSerialize());
        $rsa = PublicKeyLoader::load($rsaKey->toPEM())->withHash('sha256')->withMGFHash('sha256');
        $decrypted = $rsa->decrypt($encryptedKey);
        return $decrypted;
    }

    public function getAlgorithmName() {
        return 'RSA-OAEP-256';
    }
}
?>