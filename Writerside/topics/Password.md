# Change the password

Here you will learn, how to change the password.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\Password;

$password = new Password($settings);
$password->setUserId("12345678");
```
There are two ways to change the password, which are explained below.


## Method 1: Change the password with the current password

```php
$password->setCurrentPassword("S3cr3t");
$password->setNewPassword("!2Gjds9");
try{
    $password->change();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## Method 2: Change the password with a verification code

### Get a verification code

```php
$password->requestVerifyCode();
```

You can get the verification code with
```php
$password->getVerifyCode();
```

### Perform the password change

```php
$password->setVerificationCode("123456");
$password->setNewPassword("!2Gjds9");
try {
     $password->change();
} catch (Exception $e) {
    echo $e->getMessage();
}
```