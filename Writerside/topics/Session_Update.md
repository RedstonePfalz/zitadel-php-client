# Update a session

Here you will learn, how to update a session.

First, we have to initialize the class.
```php
use ZitadelPhpClient\Session\Update;

$update_session = new Update($settings);
```

## Set the session id and token

At the beginning, we have to set the session id and token.

```php
$update_session->setSessionId("123456789");

$update_session->setSessionToken("ABCDEFGHIJKLMNOP");
```

## Checks

You can send several checks Zitadel, such as the password or 2-factor codes. 
The session is only created if all checks are successful.

### Password

```PHP
$update_session->setPassword("S3cr3t!");
```

### External Identity Providers

You get the required values from the [IDP-class](IDP.md).

```PHP
$update_session->setIdpIntentId("123456789");
$update_session->setIdpIntentToken("ABCDEFGHIJKLMNOP");
```

### TOTP-Code

```PHP
$update_session->setTOTPCode("123456");
```

### SMS-Code

[Here, you will learn, how to get the SMS-Code](#sms-otp-code)

```PHP
$update_session->setSmsCode("123456");
```

### Email-Code

[Here, you will learn, how to get the Email-Code](#email-otp-code)


```PHP
$update_session->setEmailCode("123456");
```

## Challenges

You can set challenges, e.g. an OTP-SMS-Code. If you set a challenge, you can get the code after performing the request.

### SMS-OTP-Code

```PHP
$update_session->returnSmsCode();
```

After performing the request, you can get the code with:

```PHP
$update_session->getSmsCode();
```

### Email-OTP-Code

```PHP
$update_session->returnEmailCode();
```

After performing the request, you can get the code with:

```PHP
$update_session->getEmailCode();
```

## Lifetime

You can set a lifetime in seconds after which the session will be automatically invalidated.

```php
$update_session->setLifetime(3600);
```

## Performing the request

```PHP
try {
    $update_session->update();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

