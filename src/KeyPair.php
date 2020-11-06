<?php

namespace Spatie\Crypto;

class KeyPair
{
    protected string $digestAlgorithm;
    protected int $privateKeyBits;
    protected int $privateKeyType;

    public function __construct(
        string $digestAlgorithm = 'sha512',
        int $privateKeyBits = 4096,
        int $privateKeyType = OPENSSL_KEYTYPE_RSA
    ) {
        $this->privateKeyType = $privateKeyType;
        $this->privateKeyBits = $privateKeyBits;
        $this->digestAlgorithm = $digestAlgorithm;
    }

    public function generate(
        string $privateKeyPath = '',
        string $publicKeyPath = ''
    ): array
    {
        /** @var \OpenSSLAsymmetricKey $key */
        $asymmetricKey = openssl_pkey_new([
            "digest_alg" => $this->digestAlgorithm,
            "private_key_bits" => $this->privateKeyBits,
            "private_key_type" => $this->privateKeyType,
        ]);

        openssl_pkey_export($asymmetricKey, $privateKey);

        $rawPublicKey = openssl_pkey_get_details($asymmetricKey);

        $publicKey = $rawPublicKey['key'];

        if ($privateKeyPath !== '') {
            file_put_contents($privateKeyPath, $privateKey);
        }

        if ($publicKeyPath !== '') {
            file_put_contents($publicKeyPath, $publicKey);
        }

        return [$privateKey, $publicKey];
    }
}
