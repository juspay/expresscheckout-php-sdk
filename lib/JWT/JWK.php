<?php
namespace Juspay\JWT;
use Juspay\Exception\JuspayException;
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
        return self::loadKeyFromPEM($key);
    }

    private function loadKeyFromPEM($pem)
    {

        if (!extension_loaded('openssl')) { 
            throw new JuspayException(-1, "ERROR", "jwk_error", "Please install the OpenSSL extension");
        }
        self::sanitizePEM($pem);
        $res = openssl_pkey_get_private($pem);
        if (false === $res) {
            $res = openssl_pkey_get_public($pem);
        }
        if (false === $res) {
            throw new JuspayException(-1, "ERROR", "jwk_error", 'Unable to load the key');
        }

        $details = openssl_pkey_get_details($res);
        if (!is_array($details) || !array_key_exists('type', $details)) {
            throw new JuspayException(-1, "ERROR", "jwk_error", 'Unable to get details of the key');
        }
        switch ($details['type']) {
            case OPENSSL_KEYTYPE_RSA:
                $rsa = new RSAKey($details);
                return $rsa->values;
            default:
                throw new JuspayException(-1, "ERROR", "jwk_error", 'Unsupported key type');
        }
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