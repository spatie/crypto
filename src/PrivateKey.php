<?php

namespace Spatie\Crypto;

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
    }

    public function encrypt(string $data): string
    {
        openssl_private_encrypt($data, $decrypted, $this->privateKeyString);

        return $decrypted;
    }

    public function decrypt(string $data): string
    {
        openssl_private_decrypt($data, $decrypted, $this->privateKeyString);

        return $decrypted;
    }
}
