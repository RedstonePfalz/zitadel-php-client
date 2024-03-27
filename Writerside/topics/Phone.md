# Change the phone number

Here you will learn, how to change the phone number.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\Phone;

$phone = new Phone($settings);
$phone->setUserId("12345678");
```

## Change the phone number

Run this function:
```php
$phone->changeEmail("someone@example.com");
```
**IMPORTANT:** The phone number is ***not*** verified.
The API returns a verification code, which you can get with this function:
```php
$phone->getVerificationCode();
```

## Get a new verification code

Run this function:
```php
$phone->resendVerificationCode();
```
After that, you can get the verification code with
```php
$phone->getVerificationCode();
```

## Verify the phone number

To verify the phone number run:

```php
if ($phone->isVerified("123456")) {
    echo "Phone verified";
} else {
    echo "Verification failed";
}
```
The function returns true or false.

