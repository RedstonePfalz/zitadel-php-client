# Create a session

Here you will learn, how to create a session.  
At the end, you get a token, which is required for further updates of this session.

First, we have to initialize the class.
```php
use ZitadelPhpClient\Session\Create;

$create_session = new Create($settings);
```

## Set the user identifier

At the beginning, we have to set a user identifier. This can be the user id or the login name. You can't use both.
This will produce an error.

```php
$create_session->setUserId("123456789");
```

or

```php
$create_session->setLoginName("doe@your-zitadel-instance.com");
```

## Checks

You can send several checks Zitadel, such as the password or 2-factor codes. 
The session is only created if all checks are successful.

### Password

```PHP
$create_session->setPassword("S3cr3t!");
```

### External Identity Providers

You get the required values from the [IDP-class](IDP.md).

```PHP
$create_session->setIdpIntentId("123456789");
$create_session->setIdpIntentToken("ABCDEFGHIJKLMNOP");
```

### TOTP-Code

```PHP
$create_session->setTOTPCode("123456");
```

### SMS-Code

[Here, you will learn, how to get the SMS-Code](#sms-otp-code)

```PHP
$create_session->setSmsCode("123456");
```

### Email-Code

[Here, you will learn, how to get the Email-Code](#email-otp-code)


```PHP
$create_session->setEmailCode("123456");
```

## Challenges

You can set challenges, e.g. an OTP-SMS-Code. If you set a challenge, you can get the code after performing the request.

### SMS-OTP-Code

```PHP
$create_session->returnSmsCode();
```

After performing the request, you can get the code with:

```PHP
$create_session->getSmsCode();
```

### Email-OTP-Code

```PHP
$create_session->returnEmailCode();
```

After performing the request, you can get the code with:

```PHP
$create_session->getEmailCode();
```

## Lifetime

You can set a lifetime in seconds after which the session will be automatically invalidated.

```php
$create_session->setLifetime(3600);
```

## Performing the request

```PHP
try {
    $create_session->create();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

After performing this request, you can get the Session-ID and the Session-Token.

```PHP
$create_session->getSessionId();

$create_session->getSessionToken();
```

