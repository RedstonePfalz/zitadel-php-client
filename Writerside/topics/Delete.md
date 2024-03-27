# Delete a user

Here you will learn how to delete a user.  
If you delete a user, the state of the user will be changed to *deleted*.
The user won't be able to log in anymore and any endpoints requesting this user will return an error `User not found`.

To delete a user, simply run this commands:

```php
use ZitadelPhpClient\User\Delete;

$delete_user = new Delete($settings);
$delete_user->setUserId("12345678");

try {
    $delete_user->delete();
} catch (Exception $e) {
    echo $e->getMessage();
}
```