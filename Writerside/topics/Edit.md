# Edit a user

Here you will learn, how to edit a user.  
To change the [email address](Email.md), [the phone number](Phone.md) or the [Password](Password.md).
You can find out how to change the [email address](Email.md), [password](Password.md) or [phone number](Phone.md) on the corresponding pages in the documentation.
## Introduction

To edit a user, you have to provide the full name and the user ID:
```php
use ZitadelPhpClient\User\Edit;

$edit_user = new Edit($settings);
$edit_user->setUserId("12345678");
$edit_user->setName("John", "Doe");

// Put the edit functions here

try {
    $edit_user->edit();
} catch (Exception $e) {
    echo $e->getMessage();
}
```

## Change Nickname/Display name

To change the nickname or display name, you can use this commands:
```php
$edit_user->setNickName("NickName");
$edit_user->setDisplayName("Admin");
```

## Change username

To change the username, you can use this command:
```php
$edit_user->setUserName("SampleUser");
```

## Change language

You can change the language of the user with a shortcode.
```php
$edit_user->setLanguage("en");
```

## Change gender

You can change the gender of the user.
```php
$edit_user->setGender("GENDER_FEMALE");
```
You have the following options: `GENDER_MALE`, `GENDER_FEMALE`, `GENDER_DIVERSE` and `GENDER_UMSPECIFIED`.


