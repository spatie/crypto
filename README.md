# Encrypt and decrypt data using private/public keys

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)
![Tests](https://github.com/spatie/crypto/workflows/Tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)


This package allows you to easily generate a private/public key pair, and encrypt/decrypt messages using those keys. 

```php
use Spatie\Crypto\KeyPair;
use Spatie\Crypto\PrivateKey;
use Spatie\Crypto\PublicKey;

// generating a key pair;
[$privateKey, $publicKey] = (new KeyPair())->generate();

// when passing paths, the generate keys will to those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey)

$data = 'my secret data';

$privateKey = PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // returns something unreadable

$publicKey = PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // returns 'my secret data'
```

Most functions in this package are wrappers around `open_ssl_*` functions to improve DX.

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
[$privateKey, $publicKey] = (new Spatie\Crypto\KeyPair())->generate();
```

You can write the keys to disk, by passing paths to the `generate` function. 

```php
// when passing paths, the generate keys will to those paths
(new Spatie\Crypto\KeyPair())->generate($pathToPrivateKey, $pathToPublicKey)
```

### Loading keys

To load a key from a file use the `fromFile` static method.

```php
Spatie\Crypto\PrivateKey::fromFile($pathToPrivateKey);
Spatie\Crypto\PrivateKey::fromFile($pathToPublicKey);
```

Alternatively, you can also create a key object using a string.

```php
Spatie\Crypto\PrivateKey::fromString($privateKeyString);
Spatie\Crypto\PrivateKey::fromString($publicKeyString);
```

### Encrypting a message with a private key, decrypting with the public key

Here's how you can encrypt data using the private key, and how to decrypt it using the public key.

```php
$data = 'my secret data';

$privateKey = Spatie\Crypto\PrivateKey::fromFile($pathToPrivateKey);
$encryptedData = $privateKey->encrypt($data); // encrypted data contains something unreadable

$publicKey = Spatie\Crypto\PublicKey::fromFile($pathToPublicKey);
$decryptedData = $publicKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching private key was used to encrypt the data, or maybe tampered with the data), an exception of class `Spatie\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Encrypting a message with a public key, decrypting with the private key

Here's how you can encrypt data using the public key, and how to decrypt it using the private key.

```php
$data = 'my secret data';

$publicKey = Spatie\Crypto\PublicKey::fromFile($pathToPublicKey);
$encryptedData = $publicKey->encrypt($data); // encrypted data contains something unreadable

$privateKey = Spatie\Crypto\PrivateKey::fromFile($pathToPrivateKey);
$decryptedData = $privateKey->decrypt($encryptedData); // decrypted data contains 'my secret data'
```

If `decrypt` cannot decrypt the given data (maybe a non-matching public key was used to encrypt the data, or maybe tampered with the data), an exception of class `Spatie\Crypto\Exceptions\CouldNotDecryptData` will be thrown.

### Determining if the data can be decrypted

Both the `PublicKey` and `PrivateKey` class have a `canDecrypt` method to determine if given data can be decrypted.

```php
Spatie\Crypto\PrivateKey::fromFile($pathToPublicKey)->canDecrypt($data) // returns a boolean;
Spatie\Crypto\PublicKey::fromFile($pathToPublicKey)->canDecrypt($data) // returns a boolean;
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
