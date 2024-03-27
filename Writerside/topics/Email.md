# Change the Email address

Here you will learn, how to change the email address of the user and verifies it.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\Email;

$email = new Email($settings);
$email->setUserId("12345678");
```

## Change the email address

Run this function:
```php
$email->changeEmail("someone@example.com");
```
**IMPORTANT:** The email address is ***not*** verified.
The API returns a verification code, which you can get with this function:
```php
$email->getVerificationCode();
```

## Get a new verification code

Run this function:
```php
$email->resendVerificationCode();
```
After that, you can get the verification code with
```php
$email->getVerificationCode();
```

## Verify the email address

To verify the email address run:

```php
if ($email->isVerified("123456")) {
    echo "Email verified";
} else {
    echo "Verification failed";
}
```
The function returns true or false.

