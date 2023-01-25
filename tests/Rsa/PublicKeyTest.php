<?php

namespace Spatie\Crypto\Tests\Rsa;

use Spatie\Crypto\Rsa\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Rsa\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Rsa\Exceptions\InvalidPublicKey;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Spatie\Crypto\Tests\TestCase;

class PublicKeyTest extends TestCase
{
    /** @test */
    public function the_public_key_class_can_detect_invalid_data()
    {
        $originalData = 'secret data';
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

    /** @test */
    public function the_public_key_class_can_decrypt()
    {
        $originalData = 'secret data';
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));
        $encryptedData = $privateKey->encrypt($originalData);
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));

        $this->assertTrue($publicKey->canDecrypt($encryptedData));
    }
}
