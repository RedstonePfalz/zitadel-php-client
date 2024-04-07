# Terminate a session

Here you will learn, how to terminate a session.

First, we have to initialize the class and set the Session ID and Session Token.
```php
use ZitadelPhpClient\Session\Terminate;

$terminate_session = new Terminate($settings);
$terminate_session->setSessionId("123456789");
$terminate_session->setSessionToken("ABCDEFG");
```

You get the session ID and session Token after creating a new session.

After that, we can terminate the Session.
```php
$terminate_session->terminate();
```



