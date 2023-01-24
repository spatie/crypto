<?php
declare(strict_types = 1);

namespace Spatie\Crypto\Tests\Rsa;

use Spatie\Crypto\Rsa\Exceptions\InvalidPrivateKey;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Tests\TestCase;

class KeyPairTest extends TestCase
{
    /** @test */
    public function it_can_generate_a_private_and_public_key()
    {
        [$privateKey, $publicKey] = (new KeyPair())->generate();

        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', $privateKey);
        $this->assertStringStartsWith('-----BEGIN PUBLIC KEY-----', $publicKey);
    }

    /** @test */
    public function it_can_write_keys_to_disk()
    {
        $privateKeyPath = $this->getTempPath('privateKey');
        $publicKeyPath = $this->getTempPath('publicKey');

        if (file_exists($privateKeyPath)) {
            unlink($privateKeyPath);
        }


        if (file_exists($publicKeyPath)) {
            unlink($publicKeyPath);
        }

        (new KeyPair())->generate(
            $privateKeyPath,
            $publicKeyPath,
        );

        $this->assertStringStartsWith('-----BEGIN PRIVATE KEY-----', file_get_contents($privateKeyPath));
        $this->assertStringStartsWith('-----BEGIN PUBLIC KEY-----', file_get_contents($publicKeyPath));
    }

    /** @test */
    public function it_can_generate_a_password_protected_key()
    {
        $password = 'my-password';

        [$generatedPrivateKey] = (new KeyPair())
            ->password('my-password')
            ->generate();

        $privateKey = PrivateKey::fromString($generatedPrivateKey, $password);
        $this->assertInstanceOf(PrivateKey::class, $privateKey);

        $this->expectException(InvalidPrivateKey::class);
        PrivateKey::fromString($generatedPrivateKey, 'invalid-password');
    }
}
