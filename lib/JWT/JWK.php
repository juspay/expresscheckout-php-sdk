<?php
namespace Juspay\JWT;
use InvalidArgumentException;
use RuntimeException;
use const OPENSSL_KEYTYPE_RSA;
class JWK {
    /**
     * @var string
     */
    private $values = [];
    public function __construct($key)
    {
        $this->values = self::createFromKey($key);
    }

    private function createFromKey($key)
    {
       
        return self::loadFromKey($key);
    }



    /**
     * @param string $key
     *
     * @throws \Exception
     *
     * @return array
     */
    public function loadFromKey($key)
    {
        try {
            return self::loadKeyFromDER($key);
        } catch (\Exception $e) {
            return self::loadKeyFromPEM($key);
        }
    }

    private function loadKeyFromDER($key)
    {
        $pem = self::convertDerToPem($key);
        return self::loadKeyFromPEM($pem);
    }

    private function loadKeyFromPEM($pem)
    {

        if (!extension_loaded('openssl')) {
            throw new RuntimeException('Please install the OpenSSL extension');
        }
        self::sanitizePEM($pem);
        $res = openssl_pkey_get_private($pem);
        if (false === $res) {
            $res = openssl_pkey_get_public($pem);
        }
        if (false === $res) {
            throw new InvalidArgumentException('Unable to load the key.');
        }

        $details = openssl_pkey_get_details($res);
        if (!is_array($details) || !array_key_exists('type', $details)) {
            throw new InvalidArgumentException('Unable to get details of the key');
        }
        switch ($details['type']) {
            case OPENSSL_KEYTYPE_RSA:
                $rsa = new RSAKey($details);
                return $rsa->values;
            default:
                throw new InvalidArgumentException('Unsupported key type');
        }
    }

    private function convertDerToPem($der_data)
    {
        $pem = chunk_split(base64_encode($der_data), 64, PHP_EOL);
        return '-----BEGIN CERTIFICATE-----'.PHP_EOL.$pem.'-----END CERTIFICATE-----'.PHP_EOL;
    }

    private function sanitizePEM(&$pem)
    {
        preg_match_all('#(-.*-)#', $pem, $matches, PREG_PATTERN_ORDER);
        $ciphertext = preg_replace('#-.*-|\r|\n| #', '', $pem);

        $pem = $matches[0][0].PHP_EOL;
        $pem .= chunk_split($ciphertext, 64, PHP_EOL);
        $pem .= $matches[0][1].PHP_EOL;
    }
    public function jsonSerialize()
    {
        return $this->values;
    }

}
?>