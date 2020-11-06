<?php

namespace Spatie\Crypto;

class PublicKey
{
    protected string $publicKeyString;

    public static function fromString(string $publicKeyString): self
    {
        return new static($publicKeyString);
    }

    public static function fromFile(string $pathToPublicKey): self
    {
        $publicKeyString = file_get_contents($pathToPublicKey);

        return new static($publicKeyString);
    }

    public function __construct(string $publicKeyString)
    {
        $this->publicKeyString = $publicKeyString;
    }

    public function decrypt(string $data): string
    {
        openssl_public_decrypt($data, $decrypted, $this->publicKeyString);

        return $decrypted;
    }
}
