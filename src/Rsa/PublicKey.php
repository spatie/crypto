<?php

namespace Spatie\Crypto\Rsa;

use Spatie\Crypto\Rsa\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Rsa\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Rsa\Exceptions\InvalidPublicKey;

class PublicKey
{
    /** @var resource */
    protected $publicKey;

    public static function fromString(string $publicKeyString): self
    {
        return new static($publicKeyString);
    }

    public static function fromFile(string $pathToPublicKey): self
    {
        if (! file_exists($pathToPublicKey)) {
            throw FileDoesNotExist::make($pathToPublicKey);
        }

        $publicKeyString = file_get_contents($pathToPublicKey);

        return new static($publicKeyString);
    }

    public function __construct(string $publicKeyString)
    {
        $this->publicKey = openssl_pkey_get_public($publicKeyString);

        if ($this->publicKey === false) {
            throw InvalidPublicKey::make();
        }
    }

    public function encrypt(string $data)
    {
        openssl_public_encrypt($data, $encrypted, $this->publicKey, OPENSSL_PKCS1_OAEP_PADDING);

        return $encrypted;
    }

    public function canDecrypt(string $data): bool
    {
        try {
            $this->decrypt($data);
        } catch (CouldNotDecryptData $exception) {
            return false;
        }

        return true;
    }

    public function decrypt(string $data): string
    {
        openssl_public_decrypt($data, $decrypted, $this->publicKey, OPENSSL_PKCS1_PADDING);

        if (is_null($decrypted)) {
            throw CouldNotDecryptData::make();
        }

        return $decrypted;
    }

    public function details(): array
    {
        return openssl_pkey_get_details($this->publicKey);
    }

    public function verify(string $data, string $signature): bool
    {
        return openssl_verify($data, base64_decode($signature), $this->publicKey, OPENSSL_ALGO_SHA256);
    }
}
