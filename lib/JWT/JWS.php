<?php
namespace Juspay\JWT;
use Exception;
use Juspay\Exception\JuspayException;
use Juspay\JWT\Base64Url;
class JWS {

    /**
     * @property ISigningAlgorithm $signingAlgorithm
     */
    private $signingAlgorithm;

    /**
     * @property string $signature
     */
    private $signature;

    /**
     * @property string $payload
     */
    public $payload;

    /**
     * @property string $encodedPayload
     */
    public $encodedPayload;

    /**
     * @property string $encodedProtectedHeaders
     */
    public $encodedProtectedHeaders;

    public $protectedHeaders = [];


    public function setEncodedPayload() {
        if ($this->payload !== null) {
            $this->encodedPayload = Base64Url::encode($this->payload);
        }
    }

    public function setPayload() {
        if ($this->encodedPayload != null) {
            $this->payload = Base64Url::decode($this->encodedPayload);
        }
    }

    public function setProtectedHeaders() {
        if ($this->encodedProtectedHeaders != null) {
            $this->protectedHeaders = json_decode(Base64Url::decode($this->encodedProtectedHeaders), true);
        }
    }
    public function setEncodedProtectedHeaders() {
        if (!empty($this->protectedHeaders) || !is_null($this->protectedHeaders)) {
            $this->encodedProtectedHeaders = Base64Url::encode(json_encode($this->protectedHeaders));
        }
    }

    /**
     * @param ISigningAlgorithm $signingAlgorithm
     */
    public function __construct($signingAlgorithm) {
       $this->signingAlgorithm = $signingAlgorithm;
       $this->protectedHeaders = ["alg" => $signingAlgorithm->getAlgorithmName()];
    }

    private function getInputToSign() {
        $this->setEncodedProtectedHeaders();
        $this->setEncodedPayload();
        if ($this->encodedProtectedHeaders != null && $this->encodedPayload != null) {
            return sprintf('%s.%s', $this->encodedProtectedHeaders,  $this->encodedPayload);
        } else {
            throw new JuspayException(-1, "ERROR", "jws_error", "Unable to encode payload and header");
        }
    }
    /**
     * @param JWK $key
     * @param string $payload
     * @param array $protectedHeaders
     */
    public function createJWSandSerialize($key, $payload, $protectedHeaders = null) {
        if (array_key_exists('alg', $this->protectedHeaders)) {
            if ($protectedHeaders != null) {
                $this->protectedHeaders = array_merge($this->protectedHeaders, $protectedHeaders);
            }
        } else {
            $this->protectedHeaders = $protectedHeaders;
        }
        $this->payload = $payload;
        $encodedPayload = $this->getInputToSign();
        $this->signature = $this->signingAlgorithm->sign($encodedPayload, $key);
        return sprintf('%s.%s', $encodedPayload, Base64Url::encode($this->signature));
    }

    public function loadJWS($encodedSignedPayload) {
        try {
            $parts = explode('.', $encodedSignedPayload);
            $this->encodedPayload = $parts[1];
            $this->encodedProtectedHeaders = $parts[0];
            $this->signature = Base64Url::decode($parts[2]);
            $this->setPayload();
            $this->setProtectedHeaders();
        } catch (Exception $e) {
            throw new JuspayException(-1, "ERROR", "jws_error", $e->getMessage());
        }
    }

    public function verify($key, $encodedSignedPayload) {
        $this->loadJWS($encodedSignedPayload);
        $inputToVerify = sprintf('%s.%s', $this->encodedProtectedHeaders, $this->encodedPayload);
        if ($this->signingAlgorithm->verifySign($inputToVerify, $this->signature, $key) != 1) {
            throw new JuspayException(-1, "ERROR", "jws_error", "unable to verify token");
        };
    }
}
?>