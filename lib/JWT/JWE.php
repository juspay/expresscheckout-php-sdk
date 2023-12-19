<?php
namespace Juspay\JWT;
use Exception;
use Juspay\Exception\JuspayException;
use Juspay\JWT\Base64Url;
class JWE {
    /**
     * @var string
     */
    public $payload = null;

    /**
     * @var string
     */
    private $encryptedKey = null;

    /**
     * @var string
     */

     private $cek = null;
    /**
     * @var string|null
     */
    private $ciphertext = null;

    /**
     * @var string|null
     */
    private $iv = null;

    /**
     * @var string|null
     */
    private $aad = null;

    /**
     * @var string|null
     */
    private $tag = null;


    /**
     * @var array
     */
    private $sharedProtectedHeaders = [];

    /**
     * @var string|null
     */
    private $encodedSharedProtectedHeaders = null;

    /**
     * @var IKeyEncryption $keyEncryptionAlgorithm
     */
    private $keyEncryptionAlgorithm = null;

    /**
     * @var IContentEncryption $contentEncryptionAlgorithm
     */
    private $contentEncryptionAlgorithm = null;

    /**
     * @property IKeyEncryption $keyEncryptionAlgorithm
     * @property IContentEncryption $contentEncryptionAlgorithm
     */
    public function __construct($keyEncryptionAlgorithm, $contentEncryptionAlgorithm) {
        $this->keyEncryptionAlgorithm = $keyEncryptionAlgorithm;
        $this->contentEncryptionAlgorithm = $contentEncryptionAlgorithm;
        $this->sharedProtectedHeaders = [
            'alg' => $this->keyEncryptionAlgorithm->getAlgorithmName(),
            'enc' => $this->contentEncryptionAlgorithm->getAlgorithmName(),
        ];
    }

    public function createCek() {
        $size = $this->contentEncryptionAlgorithm->getCEKSize();
        return random_bytes($size / 8);
    }

    public function createIV() {
        $size = $this->contentEncryptionAlgorithm->getIVSize();
        return random_bytes($size / 8);
    }

    public function setEncodedSharedProtectedHeaders() {
        if (!empty($this->sharedProtectedHeaders) || !is_null($this->sharedProtectedHeaders)) {
            $this->encodedSharedProtectedHeaders = Base64Url::encode(json_encode($this->sharedProtectedHeaders));
        }
    }

    public function setSharedProtectedHeaders() {
        if ($this->encodedSharedProtectedHeaders != null) {
            $this->sharedProtectedHeaders = json_decode(Base64Url::decode($this->encodedSharedProtectedHeaders), true);
        }
    }
    public function encrypt ($publicKey, $payload) {
        $this->tag = null;
        $cek = $this->createCek();
        $this->iv = $this->createIV();
        $aad = $this->aad === null ? null : Base64Url::encode($this->aad);
        if ($this->encodedSharedProtectedHeaders === null) {
            $this->setEncodedSharedProtectedHeaders();
        }
        $cipherText = $this->contentEncryptionAlgorithm->encryptContent($payload, $cek, $this->iv, $aad, $this->encodedSharedProtectedHeaders, $this->tag);
        $this->ciphertext = $cipherText;
        $this->encryptedKey = $this->keyEncryptionAlgorithm->encryptKey($publicKey, $cek);
    }

    public function toCompactJSON() {
        return sprintf(
            '%s.%s.%s.%s.%s',
            $this->encodedSharedProtectedHeaders,
            Base64Url::encode(null === $this->encryptedKey ? '' : $this->encryptedKey),
            Base64Url::encode(null === $this->iv ? '' : $this->iv),
            Base64Url::encode($this->ciphertext),
            Base64Url::encode(null === $this->tag ? '' : $this->tag)
        );
    }
    public function createJWEAndSerialize($publicKey, $payload, $sharedProtectedHeaders ) {
        if (array_key_exists('alg', $this->sharedProtectedHeaders)) {
            if ($sharedProtectedHeaders != null) {
                $this->sharedProtectedHeaders = array_merge($this->sharedProtectedHeaders, $sharedProtectedHeaders);
            }
        } else {
            $this->sharedProtectedHeaders = $sharedProtectedHeaders;
        }
        $this->encrypt($publicKey, $payload);
        return $this->toCompactJSON();
    }

    public function loadJWE($jweContent) {
        try {
            $parts = explode('.', $jweContent);
            $this->encodedSharedProtectedHeaders = $parts[0];
            $this->setSharedProtectedHeaders();
            $this->encryptedKey = Base64Url::decode($parts[1]);
            $this->iv = Base64Url::decode($parts[2]);
            $this->ciphertext = Base64Url::decode($parts[3]);
            $this->tag = Base64Url::decode($parts[4]);
        } catch (Exception $e) {
            throw new JuspayException(-1, "ERROR", "jwe_error", $e->getMessage());
        }
    }
    public function decryptJWE($privateKey, $jweContent) {
        $this->loadJWE($jweContent);
        $cek = $this->keyEncryptionAlgorithm->decryptKey($privateKey, $this->encryptedKey);
        $this->payload = $this->contentEncryptionAlgorithm->decryptContent($this->ciphertext, $cek, $this->iv, $this->aad, $this->encodedSharedProtectedHeaders, $this->tag);
        return $this->payload;
    }

}
?>