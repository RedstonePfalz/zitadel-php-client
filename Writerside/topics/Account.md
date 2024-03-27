# Change account states

Here you will learn, how to change the account states.

## First steps

First, we have to initialize the class and set the user id.
```php
use ZitadelPhpClient\User\Account;

$account = new Account($settings);
$account->setUserId("12345678");
```

## Deactivate a user

If you deactivate a user, he won't be able to log in. Use deactivate user when the user should not be able to use the account anymore, but you still need access to the user data.
```php
$account->deactivate();
```

## Reactivate a user

If you reactivate a user, he will be able to log in again.
```php
$account->reactivate();
```

## Lock a user

If you lock a user, he won't be able to log in.
```php
$account->lock();
```

## Unlock a user

The user will be able to log in again.
```php
$account->unlock();
```