<?php

namespace Spatie\Crypto\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

class PrivateKeyTest extends TestCase
{
    /** @test */
    public function a_private_key_can_be_used_to_encrypt_a_message_that_can_be_decrypted_by_a_public_key()
    {
        $message = 'secret message';

        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $encryptedMessage = $privateKey->encrypt($message);

        $this->assertNotEquals($message, $encryptedMessage);

        $publicKey = PublicKey::fromFile()
    }

    public function getStub(string $nameOfStub): string
    {
        return __DIR__ . "/stubs/{$nameOfStub}";
    }
}
