# Get user data

Here you will learn, how to get user data.

## First steps

First, we have to initialize the class and set the user id. After that, we download the data from the API.
```php
use ZitadelPhpClient\User\Get;

$get_user = new Get($settings);
$get_user->setUserId("12345678");

try {
    $get_user->fetch();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

Now, we can access the user data.

## Get the profile picture

```php
$get_user->getProfilePicture();
```
The function returns the URL of the profile picture.  
***NOTICE:*** If the user didn't set a profile picture, you'll get an error when accessing this function.

## Get the user state

```php
$get_user->getUserState();
```
Possible values: `USER_STATE_UNSPECIFIED`, `USER_STATE_ACTIVE`, `USER_STATE_INACTIVE`, `USER_STATE_DELETED`, `USER_STATE_LOCKED`, `USER_STATE_INITIAL`

## Get the username

```php
$get_user->getUsername();
```

## Get the login names

```php
$get_user->getLoginNames();
```
This function returns an array with the possible login names.

## Get the preferred login name

```php
$get_user->getPreferredLoginName();
```

## Get the given name and family name

```php
$get_user->getGivenName();
$get_user->getFamilyName();
```

## Get the nickname

```php
$get_user->getNickname();
```

## Get the display name

```php
$get_user->getDisplayName();
```

## Get the preferred language

```php
$get_user->getPreferredLanguage();
```

## Get the gender

```php
$get_user->getGender();
```
Possible values: `GENDER_UNSPECIFIED`, `GENDER_MALE`, `GENDER_FEMALE`, `GENDER_DIVERSE`

## Get the email address

```php
$get_user->getEmail();
```

The following function returns true, if the email address is verified.

```php
$get_user->isEmailVerified();
```

## Get the phone number

```php
$get_user->getPhone();
```

The following function returns true, if the phone number is verified.

```php
$get_user->isPhoneVerified();
```

## Get the raw user data

This function returns the raw JSON-encoded user data.
```php
$get_user->getRawUserData();
```



