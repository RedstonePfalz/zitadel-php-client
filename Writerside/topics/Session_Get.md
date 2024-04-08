# Get a session

Here you will learn, how to update a session.

First, we have to initialize the class.

```php
use ZitadelPhpClient\Session\Get;

$get_session = new Get($settings);

$get_session->setSessionId("123456789");
$get_session->setSessionToken("ABCDEFGHIJKLMNOP");
```

## Perform the Request

```PHP
try {
    $get_session->get();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## Access the session data

After performing the request, you can access the data.  
If no data is sent back from the Zitadel API for e.g. 2FA tokens, an error will occur when trying to access the data.

Some functions return a date in the format `YYYY-MM-DDThh:mm:ss.fZ` e.g. `2024-04-08T14:37:09.846600Z`.

### Session data

#### Creation date

```PHP
$get_session->getCreationDate();
```

#### Change date

```PHP
$get_session->getChangeDate();
```

#### Lifetime

```PHP
$get_session->getLifetime();
```

### User data

#### User verification date

```PHP
$get_session->getUserVerifiedAt();
```

#### User Id

```PHP
$get_session->getUserId();
```

#### Login Name

Returns the login name e.g. `doe@your-zitadel-instance.com`

```PHP
$get_session->getUserLoginName();
```

#### Display Name

```PHP
$get_session->getUserDisplayName();
```

#### Organization Id

```PHP
$get_session->getUserOrganizationId();
```

### Password Verification Date

```PHP
$get_session->getPasswordVerifiedAt();
```

### IDP Verification Date

```PHP
$get_session->getIdpVerifiedAt();
```

### OTP Email Verification Date

```PHP
$get_session->getOtpEmailVerifiedAt();
```

### TOTP Verification Date

```PHP
$get_session->getTotpVerifiedAt();
```

### OTP SMS VerifiedAt

```PHP
$get_session->getSmsVerifiedAt();
```