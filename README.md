# Apple OAuth2 Provider for Laravel Socialite

** This component is under development. **

## Requirements

* PHP >= 7.1.3
* Laravel >= 5.7

## Usage

### Prepare in Apple Developer Portal

Create an App ID, Services ID and Private Key for Client Authentication in Apple Developer Portal.

see: [Getting Started - Sign in with Apple - Apple Developer](https://developer.apple.com/sign-in-with-apple/get-started/)

### Install

```
composer require seoda/socialite-apple
```

### Configure

config/services.php
```
'apple' => [
    'client_id' => env('APPLE_CLIENT_ID'),
    'client_secret' => env('APPLE_CLIENT_SECRET'),
    'redirect' => env('APPLE_REDIRECT'),
]
```

.env
```
APPLE_CLIENT_ID=
APPLE_CLIENT_SECRET=
APPLE_REDIRECT=
```

### Implemenetation

```php
// Redirect to Sign in with Apple in controller.
return Socialite::driver('apple')->redirect();

// Handle callback, fetch user information from `code` in controller.
$user = Socialite::driver('apple')->user();
```

## Limitation

* `getUserByToken()` is not implemeneted at this time.
  * This means that you cannot get `name` and `email` fields.
  * We wait more information from Apple..

## License

socialite-apple is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
