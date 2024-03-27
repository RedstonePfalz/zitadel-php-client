# Create a user

Here you will learn how to create a user. To create a user, you need to set the *serviceUserToken* in the Settings-Array.

## Simplest way

To create a user, you need the following data:

- Full name
- Email address
- Password
- Username (optional, but recommended)

The following data is optional:

- Organization membership
- Nickname
- Display name
- Language
- Gender
- Phone number
- Metadata
- User ID
- Link an Identity Provider

To create a user, you only need a few lines of code:
```php
use ZitadelPhpClient\User\Create;

$create_user = new Create($settings);
$create_user->setName("John", "Doe");
$create_user->setEmail("j.doe@example.org");
$create_user->setPassword("S3cr3t");
$create_user->setUserName("SampleUser");

try {
    $create_user->create();
} catch(Exception $e) {
    echo $e->getMessage();
}
```
The `create()`function will return an Exception, if the communication with Zitadel fails, so it's recommended to put the `create()`function into *try/catch*.

## Add organization membership

If you have multiple organizations in your Zitadel instance, you can assign the user to an organization.
```php
$create_user->setOrganization("123456", "sampleorg.your-zitadel-instance.com")
```
The first parameter is the organization ID and the second parameter is your organization domain.

## Add Nickname/Display name

To add a nickname or a display name, you can use this commands:
```php
$create_user->setNickName("NickName");
$create_user->setDisplayName("Admin");
```

## Add language

You can set the language of the user with a shortcode. If you don't provide one, the default one will be used.
```php
$create_user->setLanguage("en");
```

## Add gender

You can set the gender of the user. If you don't provide one, the gender will be set to `GENDER_UNSPECIFIED`.
```php
$create_user->setGender("GENDER_MALE");
```
You have the following options: `GENDER_MALE`, `GENDER_FEMALE` and `GENDER_DIVERSE`.

## Add phone number

To set a phone number, just call this function:
```php
$create_user->setPhone("+491590123456");
```
The phone number will be automatically marked as verified.

**IMPORTANT:** You have to provide the phone number without spaces, the first zero and with the county code e.g. +49

## Add metadata

You can link metadata with the user profile. You can run the function several times.
```php
$create_user->addMetaData("key", "value");
$create_user->addMetaData("key2", "value2");
```

The value will be automatically Base64 encoded.

## Set the userid

You can set a custom user id for the user. If you don't provide one, the user will get one from Zitadel.
```php
$create_user->setUserId("575");
```

## Link an external Identity Provider

You can link an external Identity Provider with the user account, so the user can sign in e.g. with GitHub or Google. 
```php
$create_user->addIDPLink("idpId", "userId", "userName");
```

The first parameter is the ID of the Identity Provider. You can find it in the Zitadel instance settings.  
The second parameter is the User ID of the external account.  
The third parameter is the username of the external account.