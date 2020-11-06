<?php

namespace Spatie\Crypto;

use Spatie\Crypto\Exceptions\CouldNotDecryptData;

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

    public function encrypt(string $data)
    {
        try {
            openssl_public_encrypt($data, $encrypted, $this->publicKeyString);

        } catch (\Exception $exception) {
            \dd($exception);
        }

        return $encrypted;
    }

    public function isValidData(string $data): bool
    {
        try {
            $this->decrypt($data);
        } catch (CouldNotDecryptData) {
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
}
