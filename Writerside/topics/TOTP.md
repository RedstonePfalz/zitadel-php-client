# TOTP

Here you will learn, how to set up a TOTP generator for a user.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\SetupTwoFactorAuth\TOTP;

$totp = new TOTP($settings);
$totp->setUserId("12345678");
```

## Start the TOTP setup

```php
$totp->start();
```

After running the command, you can get the TOTP-Secret, the TOTP-URI and the QR-Code for Authenticator-Apps.

### TOTP-Secret

```php
$totp->getSecret();
```

### TOTP-URI

```php
$totp->getURI();
```

### QR-Code

```php
$totp->getQRCode();
```

This function returns a Base64-encoded SVG image url e.g. `data:image/svg+xml;base64,PD94...`.

## Verify and finish the TOTP-Setup

```php
if ($totp->verify("TOTP-Code")) {
    echo "Setup finished";
} else {
    echo "Setup failed";
}
```






