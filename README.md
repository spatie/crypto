# Encrypting and signing data using private/public keys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)
![Tests](https://github.com/spatie/crypto/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)


This package allows you to easily generate a private/public key pairs, and encrypt/decrypt messages using those keys.

```php
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

// generating an RSA key pair
[$privateKey, $publicKey] = (new KeyPair())->generate();

// when passing paths, the generated keys will be written those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey);

$data = 'my secret data';

$privateKey = PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // returns something unreadable

$publicKey = PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // returns 'my secret data'
```

Most functions in this package are wrappers around `openssl_*` functions to improve DX.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-skeleton-php.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/crypto)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/crypto
```

## Usage

You can generate a key pair using the `generate` function on the `KeyPair` class.

```php
use Spatie\Crypto\Rsa\KeyPair;

[$privateKey, $publicKey] = (new KeyPair())->generate();
```

You can write the keys to disk, by passing paths to the `generate` function. 

```php
// when passing paths, the generate keys will to those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey)
```

You can protect the private key with a password by using the `password` method:

```php
[$passwordProtectedPrivateKey, $publicKey] = (new KeyPair())->password('my-password')->generate();
```

When using a password to generating a private key, you will need that password when instantiating the `PrivateKey` class.

### Loading keys

To load a key from a file use the `fromFile` static method.

```php
Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
```

Alternatively, you can also create a key object using a string.

```php
Spatie\Crypto\Rsa\PrivateKey::fromString($privateKeyString);
Spatie\Crypto\Rsa\PublicKey::fromString($publicKeyString);
```

If the private key is password protected, you need to pass the password as the second argument.

```php
Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey, $password);
Spatie\Crypto\Rsa\PrivateKey::fromString($privateKeyString, $password);
```

If you do not specify the right password, a `Spatie\Crypto\Exceptions\InvalidPrivateKey` exception will be thrown.

### Encrypting a message with a private key, decrypting with the public key

Here's how you can encrypt data using the private key, and how to decrypt it using the public key.

```php
$data = 'my secret data';

$privateKey = Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // encrypted data contains something unreadable

$publicKey = Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching private key was used to encrypt the data, or maybe tampered with the data), an exception of class `Spatie\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Encrypting a message with a public key, decrypting with the private key

Here's how you can encrypt data using the public key, and how to decrypt it using the private key.

```php
$data = 'my secret data';

$publicKey = Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);
$encryptedData = $publicKey->encrypt($data); // encrypted data contains something unreadable

$privateKey = Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey);
$decryptedData = $privateKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching public key was used to encrypt the data, or maybe tampered with the data), an exception of class `Spatie\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Determining if the data can be decrypted

Both the `PublicKey` and `PrivateKey` class have a `canDecrypt` method to determine if given data can be decrypted.

```php
Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey)->canDecrypt($data); // returns a boolean;
Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey)->canDecrypt($data); // returns a boolean;
```

### Signing and verifying data

The `PrivateKey` class has a method `sign` to generate a signature for the given data. The `verify` method on the `PublicKey` class can be used to verify if a signature is valid for the given data.

If `verify` returns `true`, you know for certain that the holder of the private key signed the message, and that it was not tampered with.

```php
$signature = Spatie\Crypto\Rsa\PrivateKey::fromFile($pathToPrivateKey)->sign('my message'); // returns a string

$publicKey = Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);

$publicKey->verify('my message', $signature) // returns true;
$publicKey->verify('my modified message', $signature) // returns false;
```

## Alternatives

This package aims to be very lightweight and easy to use. If you need more features, consider using of one these alternatives:

- [paragonie/halite](https://github.com/paragonie/halite)
- [vlucas/pikirasa](https://github.com/vlucas/pikirasa)
- [laminas/crypt](https://docs.laminas.dev/laminas-crypt/)
- [phpseclib/phpseclib](https://github.com/phpseclib/phpseclib)

## A word on the usage of RSA

At the time of writing, RSA is secure enough for the use case we've built this package for.

To know more about why RSA might not be good enough for you, read [this post on public-key encryption at Paragonie.com](https://paragonie.com/blog/2016/12/everything-you-know-about-public-key-encryption-in-php-is-wrong#php-openssl-rsa-bad-default)

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
