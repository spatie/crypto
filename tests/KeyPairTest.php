<?php

namespace Spatie\Crypto\Tests;

use PHPUnit\Framework\TestCase;
use Spatie\Crypto\KeyPair;

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
        $privateKeyPath = __DIR__ . '/temp/privateKey';
        $publicKeyPath =  __DIR__ . '/temp/publicKey';

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
}
