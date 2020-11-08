<?php

namespace Spatie\Crypto\Tests;

use Spatie\Crypto\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Exceptions\InvalidPrivateKey;
use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

class PrivateKeyTest extends TestCase
{
    /** @test */
    public function the_private_key_class_can_detect_invalid_data()
    {
        $originalData = 'secret data';
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));
        $encryptedData = $publicKey->encrypt($originalData);
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $modifiedDecrypted = $encryptedData . 'modified';
        $this->assertFalse($privateKey->canDecrypt($modifiedDecrypted));

        $this->expectException(CouldNotDecryptData::class);
        $privateKey->decrypt($modifiedDecrypted);
    }

    /** @test */
    public function it_can_get_the_details_of_the_private_key()
    {
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $details = $privateKey->details();

        $this->assertIsArray($details);
    }

    /** @test */
    public function a_private_key_will_throw_an_exception_if_it_is_invalid()
    {
        $this->expectException(InvalidPrivateKey::class);

        PrivateKey::fromString('invalid-key');
    }
}
