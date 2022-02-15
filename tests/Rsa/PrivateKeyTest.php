<?php

namespace Spatie\Crypto\Tests\Rsa;

use Spatie\Crypto\Rsa\Exceptions\CouldNotDecryptData;
use Spatie\Crypto\Rsa\Exceptions\FileDoesNotExist;
use Spatie\Crypto\Rsa\Exceptions\InvalidPrivateKey;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use Spatie\Crypto\Tests\TestCase;

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

    /** @test */
    public function it_will_throw_an_exception_when_there_is_no_private_key_file_at_the_path_given()
    {
        $this->expectException(FileDoesNotExist::class);

        PrivateKey::fromFile('non-existing-file');
    }

    /** @test */
    public function the_private_key_class_can_decrypt()
    {
        $originalData = 'secret data';
        $publicKey = PublicKey::fromFile($this->getStub('publicKey'));
        $encryptedData = $publicKey->encrypt($originalData);
        $privateKey = PrivateKey::fromFile($this->getStub('privateKey'));

        $this->assertTrue($privateKey->canDecrypt($encryptedData));
    }

    public function test_instantiating_a_private_key_that_has_password_with_no_password_should_throw_exception()
    {
        $this->expectException(InvalidPrivateKey::class);

        $password = "super-strong-password";
        [$passwordProtectedPrivateKey, $publicKey] = (new KeyPair())->password($password)->generate();

        PrivateKey::fromString($passwordProtectedPrivateKey);
    }
}
