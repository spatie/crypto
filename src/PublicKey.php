<?php

namespace Spatie\Crypto;

use Spatie\Crypto\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Exceptions\InvalidPublicKey;

class PublicKey
{
    protected string $publicKeyString;

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
        $this->publicKeyString = $publicKeyString;

        if (! $this->isValidKey()) {
            throw InvalidPublicKey::make();
        }
    }

    public function encrypt(string $data)
    {
        openssl_public_encrypt($data, $encrypted, $this->publicKeyString);

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
        openssl_public_decrypt($data, $decrypted, $this->publicKeyString);

        if (is_null($decrypted)) {
            throw CouldNotDecryptData::make();
        }

        return $decrypted;
    }

    public function isValidKey(): bool
    {
        $key = openssl_pkey_get_public($this->publicKeyString);

        return (bool)$key;
    }

    public function details(): array
    {
        $key = openssl_pkey_get_public($this->publicKeyString);

        return openssl_pkey_get_details($key);
    }
}
