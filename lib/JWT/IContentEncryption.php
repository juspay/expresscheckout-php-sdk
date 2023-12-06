<?php
namespace Juspay\JWT;
abstract class IContentEncryption {
    abstract public function encryptContent($content, $cek, $iv, $aad, $protectedHeaders, &$tag);

    abstract public function decryptContent($content, $cek, $iv, $aad, $protectedHeaders, $tag);

    abstract public function getAlgorithmName();

    abstract public function getIVSize();

    abstract public function getCEKSize();
}
?>