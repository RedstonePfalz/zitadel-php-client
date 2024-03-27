# SMS

Here you will learn, how to set up an OTP SMS for a user.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\SetupTwoFactorAuth\SMS;

$sms = new SMS($settings);
$sms->setUserId("12345678");
```

## Add the OTP SMS method

```php
$sms->add();
```

To use the function, the phone number has to be verified. Otherwise, you'll get an Exception.

## Remove the OTP SMS method

```php
$sms->remove();
```
