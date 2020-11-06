<?php

namespace Spatie\Crypto;

use OpenSSLAsymmetricKey;
use Spatie\Crypto\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Exceptions\InvalidPrivateKey;

class PrivateKey
{
    protected string $privateKeyString;

    public static function fromString(string $privateKeyString): self
    {
        return new static($privateKeyString);
    }

    public static function fromFile(string $pathToPrivateKey): self
    {
        $privateKeyString = file_get_contents($pathToPrivateKey);

        return new static($privateKeyString);
    }

    public function __construct(string $privateKeyString)
    {
        $this->privateKeyString = $privateKeyString;

        if (! $this->isValidKey()) {
            throw InvalidPrivateKey::make();
        }
    }

    public function encrypt(string $data): string
    {
        openssl_private_encrypt($data, $decrypted, $this->privateKeyString);

        return $decrypted;
    }

    public function isValidData(string $data): bool
    {
        try {
            $this->decrypt($data);
        }
        catch (CouldNotDecryptData) {
            return false;
        }

        return true;
    }

    public function decrypt(string $data): string
    {
        openssl_private_decrypt($data, $decrypted, $this->privateKeyString);

        if (is_null($decrypted)) {
            throw CouldNotDecryptData::make();
        }

        return $decrypted;
    }

    public function isValidKey(): bool
    {
        $key = openssl_pkey_get_private($this->privateKeyString);

        return $key instanceof OpenSSLAsymmetricKey;
    }

    public function details(): array
    {
        $key = openssl_pkey_get_private($this->privateKeyString);

        return openssl_pkey_get_details($key);
    }
}
