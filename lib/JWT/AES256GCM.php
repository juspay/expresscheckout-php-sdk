<?php
namespace Juspay\JWT;
use Juspay\JWT\IContentEncryption;
use phpseclib3\Crypt\AES;
class AES256GCM extends IContentEncryption {
    public function encryptContent($data, $cek, $iv, $aad, $encodedProtectedHeaders, &$tag) {
        $calculatedAad = $encodedProtectedHeaders;
        if (null !== $aad) {
            $calculatedAad .= '.'.$aad;
        }

        $aes = new AES('gcm');
        $aes->setKeyLength($this->getCEKSize());
        $aes->setNonce($iv);
        $aes->setKey($cek);
        $aes->setAAD($calculatedAad);
        $cipherText = $aes->encrypt($data);
        $tag = $aes->getTag();
        return $cipherText;
    }
    public function decryptContent($data, $cek, $iv, $aad, $encodedProtectedHeaders, $tag) {
        $calculatedAad = $encodedProtectedHeaders;
        if (null !== $aad) {
            $calculatedAad .= '.'.$aad;
        }
        $aes = new AES('gcm');
        $aes->setNonce($iv);
        $aes->setKey($cek);
        $aes->setAAD($calculatedAad);
        $aes->setTag($tag);
        return $aes->decrypt($data);
    }
    
    public function getIVSize() {
        return 96;
    }

    public function getCEKSize() {
        return 256;
    }

    public function getAlgorithmName() {
        return 'A256GCM';
    }
}
?>