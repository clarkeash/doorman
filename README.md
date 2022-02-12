# Doorman

<p align="center">
  <a href="https://github.com/clarkeash/laravel-http-stats/actions?query=workflow%3ACI">
    <img alt="GitHub Workflow Status" src="https://img.shields.io/github/workflow/status/clarkeash/doorman/CI?logo=github&style=for-the-badge">
  </a>
  <a href="https://github.com/clarkeash/doorman/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/clarkeash/doorman.svg?style=for-the-badge">
  </a>
  <a href="https://packagist.org/packages/clarkeash/doorman">
    <img alt="GitHub release (latest SemVer)" src="https://img.shields.io/github/v/release/clarkeash/doorman?sort=semver&style=for-the-badge">
  </a>  
  <a href="https://twitter.com/clarkeash">
    <img src="http://img.shields.io/badge/author-@clarkeash-blue.svg?style=for-the-badge">
  </a>
</p>

Doorman provides a way to limit access to your Laravel applications by using invite codes.

Invite Codes:
* Can be tied to a specific email address.
* Can be available to anyone (great for sharing on social media).
* Can have a limited number of uses or unlimited.
* Can have an expiry date, or never expire.

## Laravel Support

 Laravel  | Doorman
:---------|:----------
 5.x      | 3.x
 6.x      | 4.x
 7.x      | 5.x
 8.x      | 6.x
 9.x      | 7.x

## Installation

You can pull in the package using [composer](https://getcomposer.org):

```bash
$ composer require "clarkeash/doorman=^7.0"
```

Next, migrate the database:

```bash
$ php artisan migrate
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

Make an invite with 10 redemptions and no expiry.
```php
Doorman::generate()->uses(10)->make();
```

Make an invite with unlimited redemptions and no expiry.
```php
Doorman::generate()->unlimited()->make();
```

Make an invite that expires on a specific date.
```php
$date = Carbon::now('UTC')->addDays(7);
Doorman::generate()->expiresOn($date)->make();
```

Make an invite that expires in 14 days.
```php
Doorman::generate()->expiresIn(14)->make();
```

Make an invite for a specific person.
```php
Doorman::generate()->for('me@ashleyclarke.me')->make();
```

Alternatively instead of calling `make()` which will return a collection of invites you can call `once()` if you only want a single invite generated.
```php
$invite = Doorman::generate()->for('me@ashleyclarke.me')->once();
dd($invite->code);
```


### Redeem Invites

You can redeem an invite by calling the ````redeem```` method. Providing the invite code and optionally an email address.

```php
Doorman::redeem('ABCDE');
// or
Doorman::redeem('ABCDE', 'me@ashleyclarke.me');
```

If doorman is able to redeem the invite code it will increment the number of redemptions by 1, otherwise it will throw an exception.

* ````InvalidInviteCode```` is thrown if the code does not exist in the database.
* ````ExpiredInviteCode```` is thrown if an expiry date is set and it is in the past.
* ````MaxUsesReached```` is thrown if the invite code has already been used the maximum number of times.
* ````NotYourInviteCode```` is thrown if the email address for the invite does match the one provided during redemption, or one was not provided during redemption.

All of the above exceptions extend ````DoormanException```` so you can catch that exception if your application does not need to do anything specific for the above exceptions.

```php
try {
    Doorman::redeem(request()->get('code'), request()->get('email'));
} catch (DoormanException $e) {
    return response()->json(['error' => $e->getMessage()], 422);
}
```

### Check Invites without redeeming them

You can check an invite by calling the ````check```` method. Providing the invite code and optionally an email address. (It has the same signature as the ````redeem```` method except it will return ````true```` or ````false```` instead of throwing an exception.

```php
Doorman::check('ABCDE');
// or
Doorman::check('ABCDE', 'me@ashleyclarke.me');
```

### Change Error Messages (and translation support)

In order to change the error message returned from doorman, we need to publish the language files like so:

```bash
$ php artisan vendor:publish --tag=doorman-translations
```

The language files will then be in ````/resources/lang/vendor/doorman/en```` where you can edit the ````messages.php```` file, and these messages will be used by doorman. You can create support for other languages by creating extra folders with a ````messages.php```` file in the ````/resources/lang/vendor/doorman```` directory such as ````de```` where you could place your German translations. [Read the localisation docs for more info](https://laravel.com/docs/localization).

### Validation

If you would perfer to validate an invite code before you attempt to redeem it or you are using [Form Requests](https://laravel.com/docs/5.4/validation#form-request-validation) then you can validate it like so:

```php
public function store(Request $request)
{
    $this->validate($request, [
        'email' => 'required|email|unique:users',
        'code' => ['required', new DoormanRule($request->get('email'))],
    ]);

    // Add the user to the database.
}
```

You should pass the email address into the constructor to validate the code against that email. If you know the code can be used with any email, then you can leave the parameter empty.

### Config - change table name

First publish the package configuration:

```bash
$ php artisan vendor:publish --tag=doorman-config
```

In `config/doorman.php` you will see:

```php
return [
    'invite_table_name' => 'invites',
];
```
 If you change the table name and then run your migrations Doorman will then use the new table name.
 
 ### Console
 
 To remove used and expired invites you can use the `cleanup` command:
 
 ```bash
$ php artisan doorman:cleanup
```
