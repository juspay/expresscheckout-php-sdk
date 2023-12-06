<?php
namespace Juspay\JWT;

use Juspay\JWT\Base64Url;
use InvalidArgumentException;

class RSAKey {

    public $values = [];
    public function __construct($details) {
        if (array_key_exists('kty', $details)) {
            $this->values = $details;
        
        } else {
            if (!array_key_exists('rsa', $details)) {
                throw new InvalidArgumentException('Unable to get details of the rsa key');
            }
            $this->values['kty'] = 'RSA';
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
            if (!$this->isPrivate()) {
                $this->values['key'] = $details['key'];
            }
        }
    }

     /**
     * @return string
     */
    public function toPEM()
    {
        if (self::isPrivate()) {
            $rsaParameters = [];
            $rsaParameters['n'] = Base64Url::decode($this->values['n']);
            $rsaParameters['e'] = Base64Url::decode($this->values['e']);
            $rsaParameters['d'] = Base64Url::decode($this->values['d']);
            $rsaParameters['p'] = Base64Url::decode($this->values['p']);
            $rsaParameters['q'] = Base64Url::decode($this->values['q']);
            $rsaParameters['dmp1'] = Base64Url::decode($this->values['dp']);
            $rsaParameters['dmq1'] = Base64Url::decode($this->values['dq']);
            $rsaParameters['iqmp'] = Base64Url::decode($this->values['qi']);
            $keyResource = openssl_pkey_new([
                'rsa' => $rsaParameters,
            ]);
            openssl_pkey_export($keyResource, $pemKey); 
            return $pemKey;
        } else {
            return $this->values["key"];
        }
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