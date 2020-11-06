<?php

namespace Spatie\Crypto\Tests;

use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

class KeyTest extends TestCase
{
    /** @test */
    public function a_private_key_can_be_used_to_encrypt_a_Data_that_can_be_decrypted_by_a_public_key()
    {
        $originalData = 'secret data';

        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $encryptedData = $privateKey->encrypt($originalData);

        $this->assertNotEquals($originalData, $encryptedData);

        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $decryptedData = $publicKey->decrypt($encryptedData);

        $this->assertEquals($decryptedData, $originalData);
    }

    /** @test */
    public function a_public_key_can_be_used_to_encrypt_a_Data_that_can_be_decrypted_by_a_private_key()
    {
        $originalData = 'secret data';

        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $encryptedData = $publicKey->encrypt($originalData);

        $this->assertNotEquals($originalData, $encryptedData);

        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $decryptedData = $privateKey->decrypt($encryptedData);

        $this->assertEquals($decryptedData, $originalData);
    }
}
