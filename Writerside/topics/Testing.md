# Testing

You can find the PHPUnit tests in the `test/` folder.
To run the tests, execute `phpunit` in the root directory.

## settings.php

The tests need a valid ZITADEL instance URL and a Service User Token.
You can configure them in the settings.php in the `test` folder.
Simply rename the `settings.template` in `settings.php` and fill in the values.
Otherwise, all tests will fail.