# Encrypt and decrypt messages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/spatie/crypto/run-tests?label=tests)](https://github.com/spatie/crypto/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/crypto.svg?style=flat-square)](https://packagist.org/packages/spatie/crypto)


This package allows you to easily generate a private/public key pair, and encrypt/decrypt messages using those keys. 

```php
// generating a key pair;
[$privateKey, $publicKey] = (new KeyPair())->generate();

// when passing paths, the generate keys will to those paths
(new KeyPair())->generate($pathToPrivateKey, $pathToPublicKey)

Spatie\Crypto\KeyPair::options()->generate($pathToPrivateKey, $pathToPublicKey)

$data = 'my secret message'

$encryptedData = PrivateKey::load($pathToPrivateKey)->encrypt($data);

$decryptedData = PublicKey::load($pathToPublicKey)->decrypt($encryptedData)
```


These functions are basically wrappers around `open_ssl_*` functions to improve DX.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/package-skeleton-php.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/package-skeleton-php)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

You can install the package via composer:

```bash
composer require spatie/crypto
```

## Usage

``` php
$skeleton = new Spatie\Crypto();
echo $skeleton->echoPhrase('Hello, Spatie!');
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
