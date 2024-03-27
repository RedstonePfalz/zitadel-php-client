# Identity Providers

Here you will learn, how to work with external Identity Providers.

## First steps

First, we have to initialize the class and set the user id. After that, we download the data from the API.
```php
use ZitadelPhpClient\User\IDP;

$idp = new IDP($settings);
$idp->setUserId("12345678");
$idp->setIdpId("internal-id-of-the-idp");
```

## Start the IDP flow

With this function, you start the OAuth Flow. If the request is successful, the user will be redirected to the success URL with the GET Parameters id(IDP-Intent-ID) and token(IDP-Token).  
```php
$idp->setSuccessUrl("https://example.com/success");
$idp->setFailureUrl("https://example.com/failure");

try {
    $idp->startFlow();
} catch (Exception $e) {
    echo $e->getMessage();
}
```
The API returns a URL, which can you get with
```php
$idp->getAuthUrl();
```
Then, you can redirect the user to the returned URL.  

## Fetch the data from the IDP

```php
$idp->setIdpIntentId("123456789");
$idp->setIdpToken("987654321");

try {
    $idp->fetchIdpData();
} catch (Exception $e) {
    echo $e->getMessage();
}
```
You find the IDP-Intent-ID and the IDP-Token in the GET-Parameters of your Success URL.

### Get IDP Access Token

```php
$idp->getAccessToken();
```

### Get the IDP User ID

```php
$idp->getIdpUserId();
```

### Get the IDP Username

```php
$idp->getIdpUserName();
```

### Get the IDP Email

```php
$idp->getIdpEmail();
$idp->isIdpEmailVerified();
```

### Get the IDP Profile Picture

```php
$idp->getIdpPicture();
```

### Get the IDP Profile URL

```php
$idp->getIdpProfile();
```

### Get the Raw data returned from the IDP

```php
$idp->getIdpRawInformation();
```
This function returns the raw JSON-encoded user data from the IDP

## Link the external IDP with a user account

If you link the external IDP with a user account, the user will be able to sign in through e.g. Google or GitHub

```php
$idp->linkIdpToUser();
```

