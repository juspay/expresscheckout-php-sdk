<?php
namespace Juspay\JWT;

use Juspay\Exception\JuspayException;
use Juspay\JWT\Base64Url;
use phpseclib3\Crypt\PublicKeyLoader;

class RSAKey {

    public $values = [];
    public function __construct($details) {
        if (array_key_exists('kty', $details)) {
            $this->values = $details;
        
        } else {
            if (!array_key_exists('rsa', $details)) {
                throw new JuspayException(-1, "ERROR", "jwk_error", 'Unable to get details of the rsa key');
            }
            $keys = [
                'n' => 'n',
                'e' => 'e',
                'd' => 'd',
                'p' => 'p',
                'q' => 'q',
                'dp' => 'dmp1',
                'dq' => 'dmq1',
                'qi' => 'iqmp',
            ];
            foreach ($details['rsa'] as $key => $value) {
                if (in_array($key, $keys)) {
                    $value = Base64Url::encode($value);
                    $this->values[array_search($key, $keys)] = $value;
                }
            }
            $this->values['kty'] = 'RSA';
        }
    }

     /**
     * @return string
     */
    public function toPEM()
    {
        $jwkParams = $this->values;
        $jwkParams['kty'] = 'RSA';
        $jwkKey["keys"] = [$jwkParams];
        $jwkKey = json_encode($jwkKey);
        $key = PublicKeyLoader::load($jwkKey);
        return $key->toString('PKCS8');
    }

    /**
     * @return bool
     */
    public function isPrivate()
    {
        return array_key_exists('d', $this->values);
    }
  
}
?>