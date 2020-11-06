<?php

namespace Spatie\Crypto\Tests;

use Spatie\Crypto\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Exceptions\InvalidPublicKey;
use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

class PublicKeyTest extends TestCase
{
    /** @test */
    public function the_public_key_class_can_detect_invalid_data()
    {
        $originalData = 'secret date';
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));
        $encryptedData = $privateKey->encrypt($originalData);
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $modifiedDecrypted = $encryptedData . 'modified';
        $this->assertFalse($publicKey->canDecrypt($modifiedDecrypted));

        $this->expectException(CouldNotDecryptData::class);
        $publicKey->decrypt($modifiedDecrypted);
    }

    /** @test */
    public function it_can_get_the_details_of_the_public_key()
    {
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $details = $publicKey->details();

        $this->assertIsArray($details);
    }

    /** @test */
    public function a_public_key_will_throw_an_exception_if_it_is_invalid()
    {
        $this->expectException(InvalidPublicKey::class);

        PublicKey::fromString('invalid-key');
    }

    /** @test */
    public function it_will_throw_an_exception_when_there_is_no_public_key_file_at_the_path_given()
    {
        $this->expectException(FileDoesNotExist::class);

        PublicKey::fromFile('non-existing-file');
    }
}
