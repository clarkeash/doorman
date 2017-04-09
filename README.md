# Doorman

<p align="center">
  <a href="https://travis-ci.org/clarkeash/doorman">
    <img src="https://img.shields.io/travis/clarkeash/doorman.svg?style=flat-square">
  </a>
  <a href="https://codecov.io/gh/clarkeash/doorman">
    <img src="https://img.shields.io/codecov/c/github/clarkeash/doorman.svg?style=flat-square">
  </a>
  <a href="https://scrutinizer-ci.com/g/clarkeash/doorman">
    <img src="https://img.shields.io/scrutinizer/g/clarkeash/doorman.svg?style=flat-square">
  </a>
  <a href="https://github.com/clarkeash/doorman/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/clarkeash/doorman.svg?style=flat-square">
  </a>
  <a href="https://twitter.com/clarkeash">
    <img src="http://img.shields.io/badge/author-@clarkeash-blue.svg?style=flat-square">
  </a>
</p>

Doorman provides a way to limit access to your Laravel applications by using invite codes.

Invite Codes:
* Can be tied to a specific email address.
* Can available to anyone (great for sharing on social media).
* Can have a limited number of uses or unlimited.
* Can have an expiry date, or never expire.

## Installation

You can pull in the package using [composer](https://getcomposer.org):

```bash
$ composer require clarkeash/doorman
```

Next, register the service provider with Laravel:

```php
// config/app.php
'providers' => [
    ...
    Clarkeash\Doorman\Providers\DoormanServiceProvider::class,
];
```

And, register the facade:

```php
// config/app.php
'aliases' => [
    ...
    'Doorman' => Clarkeash\Doorman\Facades\Doorman::class,
];
```

## Usage

### Generate Invites

Make a single generic invite code with 1 redemption, and no expiry.
```php
Doorman::generate()->make();
```

Make 5 generic invite codes with 1 redemption each, and no expiry.
```php
Doorman::generate()->times(5)->make();
```
