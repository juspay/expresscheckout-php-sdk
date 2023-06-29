<?php
namespace Juspay\Model;

use Exception;
use Jose\Component\Encryption\Algorithm\ContentEncryption\A256GCM;
use Jose\Component\Encryption\Algorithm\KeyEncryption\RSAOAEP256;
use Jose\Component\Encryption\Compression\CompressionMethodManager;
use Jose\Component\Encryption\JWEBuilder;
use Jose\Component\Core\AlgorithmManager;
use Jose\Component\Encryption\JWEDecrypter;
use Jose\Component\Encryption\JWELoader;
use Jose\Component\Encryption\Serializer\CompactSerializer;


use Jose\Component\Encryption\Serializer\JWESerializerManager;
use Jose\Component\KeyManagement\JWKFactory;


class EncRSAOEAP extends IEnc {

    public $kid;
    public function __construct(string $kid) {
        $this->kid = $kid;
    }
    public function encrypt(string $publicKey, string $payload) : string {
        $publicJWKKey = JWKFactory::createFromKey($publicKey);
        if (version_compare(phpversion(), '7.2.0', '>=')) {
            $jweBuilder = new JWEBuilder(
                new AlgorithmManager([new RSAOAEP256()]),   
                new AlgorithmManager([new A256GCM()]),
                new CompressionMethodManager([
                ])
            );
        } else {
            $jweBuilder = new JWEBuilder(
                null,
                new AlgorithmManager([new RSAOAEP256()]),   
                new AlgorithmManager([new A256GCM()]),
                new CompressionMethodManager([
                ])
            );
        }
        $jweVar = $jweBuilder->create()->withPayload($payload)->withSharedProtectedHeader([
            'alg' => 'RSA-OAEP-256',    
            'enc' => 'A256GCM',
            'kid' => $this->kid,
        ])->addRecipient($publicJWKKey)->build();
        
        $serializer = new CompactSerializer(); 
        
        return $serializer->serialize($jweVar, 0);
        
    }

    public function decrypt(string $privateKey, string $encryptedPayload) : string {
        $privateJWKKey = JWKFactory::createFromKey($privateKey);
        $serializerManager = new JWESerializerManager([
            new CompactSerializer(),
        ]);
        $jweDecrypter = new JWEDecrypter(
            new AlgorithmManager([new RSAOAEP256()]),
            new AlgorithmManager([new A256GCM()]),
            new CompressionMethodManager([
            ])
        );
        $jweLoader = new JWELoader(
            $serializerManager,
            $jweDecrypter,
            null
        );
        $jwe = $jweLoader->loadAndDecryptWithKey($encryptedPayload, $privateJWKKey, $recipient);
        if ($jwe->getSharedProtectedHeader()["alg"] == "RSA-OAEP-256") {
            return $jwe->getPayload();
        }
        throw new Exception('Unable to load and verify the token.');
    }
}
?>