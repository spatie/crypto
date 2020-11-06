<?php

namespace Spatie\Crypto\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

class KeyTest extends TestCase
{
    /** @test */
    public function a_private_key_can_be_used_to_encrypt_a_message_that_can_be_decrypted_by_a_public_key()
    {
        $originalMessage = 'secret message';

        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $encryptedMessage = $privateKey->encrypt($originalMessage);

        $this->assertNotEquals($originalMessage, $encryptedMessage);

        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $decryptedMessage = $publicKey->decrypt($encryptedMessage);

        $this->assertEquals($decryptedMessage, $originalMessage);
    }

    /** @test */
    public function a_public_key_can_be_used_to_encrypt_a_message_that_can_be_decrypted_by_a_private_key()
    {
        $originalMessage = 'secret message';

        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $encryptedMessage = $publicKey->encrypt($originalMessage);

        $this->assertNotEquals($originalMessage, $encryptedMessage);

        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $decryptedMessage = $privateKey->decrypt($encryptedMessage);

        $this->assertEquals($decryptedMessage, $originalMessage);
    }

    public function getStub(string $nameOfStub): string
    {
        return __DIR__ . "/stubs/{$nameOfStub}";
    }
}
