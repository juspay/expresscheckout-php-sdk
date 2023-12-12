<?php
namespace Juspay\Test;

use Exception;
use Juspay\JWT\Base64Url;
use Juspay\JWT\AES256GCM;
use Juspay\JWT\JWE;
use Juspay\JWT\JWK;
use Juspay\JWT\JWS;
use Juspay\JWT\RSA256;
use Juspay\JWT\RSAKey;
use Juspay\JWT\RSAOAEP256;

class JWTTest extends TestCase {
    public function testReadPrivateKeyInPkcs1(){
        $keyString = file_get_contents("./tests/testPrivateKeyPkcs1.pem");
        $keyStringPkcs8 = file_get_contents("./tests/testPrivateKeyPkcs8.pem");
        $key = new JWK($keyString);
        $RsaKey = new RSAKey($key->jsonSerialize());
        $this->assertTrue(preg_replace('#|\r|\n| #', '',$keyStringPkcs8) == preg_replace('#|\r|\n| #', '',$RsaKey->toPEM()));
    }

    public function testReadPrivateKeyInPkcs8() {
        $keyString = file_get_contents("./tests/testPrivateKeyPkcs8.pem");
        $key = new JWK($keyString);
        $RsaKey = new RSAKey($key->jsonSerialize());
        $this->assertTrue(preg_replace('#|\r|\n| #', '',$keyString) == preg_replace('#|\r|\n| #', '',$RsaKey->toPEM()));
    }

    public function testSigning() {
        $keyString = file_get_contents("./tests/privateKey.pem");
        $key = new JWK($keyString);
        $jws = new JWS(new RSA256());
        $payload = "hello world";
        $jwsJuspay = $jws->createJWSandSerialize($key, $payload, ['kid' => "key_xxxxx"]);
        $this->assertTrue("eyJhbGciOiJSUzI1NiIsImtpZCI6ImtleV94eHh4eCJ9.aGVsbG8gd29ybGQ.NYXOlfiLgR9JzKi9t23CkaHVfrYRQRM2MV1v2piMFHldpKG5MI9CcE444P-9C17YanGFpC4ll_aDz9sWOUEGZHg6l_hp2WtrZqZ4YlC9NaXdq2e7Qen6ABE1R-W6XZGYDHkTgtkcGaNDr-jA4U9JEaTfB0dd5-217hVA5yHnhICW3J3llDgRNibU9TyRtq8ijO0ye0cJNjr47ugm_Dx6slSVB6QayT8sfBTHULsIVL3LX9DQNnWOGWG_ck6usjKzPYMMurcedOgUTkLOJcPIdeeAHrm27OD17L3I8viTCqrqxpoiYTjJvYaa7cGe7rH0-T2QH8NOqWwd2BZSCo93oA" == $jwsJuspay);
        return $jwsJuspay;
    }

    public function testVerifySignature($signedPayload = null) {
        $keyString = file_get_contents("./tests/publicPairPrivate.pem");
        $signedPayload = null === $signedPayload ? "eyJhbGciOiJSUzI1NiIsImtpZCI6ImtleV94eHh4eCJ9.aGVsbG8gd29ybGQ.NYXOlfiLgR9JzKi9t23CkaHVfrYRQRM2MV1v2piMFHldpKG5MI9CcE444P-9C17YanGFpC4ll_aDz9sWOUEGZHg6l_hp2WtrZqZ4YlC9NaXdq2e7Qen6ABE1R-W6XZGYDHkTgtkcGaNDr-jA4U9JEaTfB0dd5-217hVA5yHnhICW3J3llDgRNibU9TyRtq8ijO0ye0cJNjr47ugm_Dx6slSVB6QayT8sfBTHULsIVL3LX9DQNnWOGWG_ck6usjKzPYMMurcedOgUTkLOJcPIdeeAHrm27OD17L3I8viTCqrqxpoiYTjJvYaa7cGe7rH0-T2QH8NOqWwd2BZSCo93oA" : $signedPayload;
        $key = new JWK($keyString);
        $jwsJuspay = new JWS(new RSA256());
        $jwsJuspay->verify($key, $signedPayload);
        $this->assertTrue(null !== $jwsJuspay->payload);
    }

    public function testVerifySignatureFailureCase() {
        $keyString = file_get_contents("./tests/publicPairPrivate.pem");
        $signedPayload = "eyJhbGciOiJSUzI1NiJ9.aGVsbG8gd29ybGQ.NYXOlfiLgR9JzKi9t23CkaHVfrYRQRM2MV1v2piMFHldpKG5MI9CcE444P-9C17YanGFpC4ll_aDz9sWOUEGZHg6l_hp2WtrZqZ4YlC9NaXdq2e7Qen6ABE1R-W6XZGYDHkTgtkcGaNDr-jA4U9JEaTfB0dd5-217hVA5yHnhICW3J3llDgRNibU9TyRtq8ijO0ye0cJNjr47ugm_Dx6slSVB6QayT8sfBTHULsIVL3LX9DQNnWOGWG_ck6usjKzPYMMurcedOgUTkLOJcPIdeeAHrm27OD17L3I8viTCqrqxpoiYTjJvYaa7cGe7rH0-T2QH8NOqWwd2BZSCo93oA";
        $key = new JWK($keyString);
        $jwsJuspay = new JWS(new RSA256());
        try {
            $jwsJuspay->verify($key, $signedPayload);
        } catch (Exception $e) {
           $this->assertTrue($e->getMessage() == "unable to verify token");
        }   
    }
    public function keyEncryption() {
        $cek = base64_decode("6H6blPsPec6BmobOn/92haednPljSamTWFLCMwhvpzo=");
        $keyString = file_get_contents("./tests/publicPairPrivate.pem");
        $key = new JWK($keyString);
        $rsaOeap = new RSAOAEP256();
        $encryptedContent = $rsaOeap->encryptKey($key, $cek);
        $this->assertTrue($encryptedContent != null);
        return $encryptedContent;
    }
    public function testKeyDecryption() {
        $encryptedKey = $this->keyEncryption();
        $keyString = file_get_contents("./tests/privateKey.pem");
        $key = new JWK($keyString);
        $rsaOeap = new RSAOAEP256();
        $decryptedKey = $rsaOeap->decryptKey($key, $encryptedKey);
        $this->assertTrue("6H6blPsPec6BmobOn/92haednPljSamTWFLCMwhvpzo=" === base64_encode($decryptedKey));
    }

    public function contentEncryption() {
        $payload = 'hello world';
        $privateKey = file_get_contents("./tests/privateKey.pem");
        $privateKeyJuspay = new JWK($privateKey);
        $jws = new JWS(new RSA256());
        $signedPayload = $jws->createJWSandSerialize($privateKeyJuspay, $payload,  ['kid' => "key_xxxxx"]);
        $signedPayload = explode(".", $signedPayload);
        $signedPayload = "{\"header\":\"{$signedPayload[0]}\",\"payload\":\"{$signedPayload[1]}\",\"signature\":\"{$signedPayload[2]}\"}";
        $publicKey = file_get_contents("./tests/publicPairPrivate.pem");
        $publicKeyJuspay = new JWK($publicKey);
        $JWE = new JWE(new RSAOAEP256(), new AES256GCM());
        $jweJuspay = $JWE->createJWEAndSerialize($publicKeyJuspay, $signedPayload, ['kid' => "key_xxxxx"]);
        $encryptedPayload = explode('.', $jweJuspay);
        $this->assertTrue($encryptedPayload !== null);
        $headers = json_decode(base64_decode($encryptedPayload[0]), true);
        $this->assertTrue($headers["kid"] === "key_xxxxx");
        $this->assertTrue($headers["alg"] === "RSA-OAEP-256");
        $this->assertTrue($headers["enc"] === "A256GCM");
        return $encryptedPayload;
    }

    public function testContentDecryption() {
        $encryptedPayload = $this->contentEncryption();
        $payload = "{$encryptedPayload[0]}.{$encryptedPayload[1]}.{$encryptedPayload[2]}.{$encryptedPayload[3]}.{$encryptedPayload[4]}";
        $privateKey = file_get_contents("./tests/privateKey.pem");
        $privateKeyJuspay = new JWK($privateKey);
        $JWE = new JWE(new RSAOAEP256(), new AES256GCM());
        $signedPayload = json_decode($JWE->decryptJWE($privateKeyJuspay, $payload), true);
        $this->assertTrue(Base64Url::decode($signedPayload["payload"]) === 'hello world');
        $signedPayload = "{$signedPayload["header"]}.{$signedPayload["payload"]}.{$signedPayload["signature"]}";
        $this->testVerifySignature($signedPayload);
    }
}
require_once __DIR__ . '/TestEnvironment.php';
?>