# Basics

First, install the library via Composer:
```Bash
composer require redstonepfalz/zitadel-php-client
```

Then, import the Composer autoload in your PHP project.
```php
<?php
require_once("vendor/autoload.php");
```

Before we import the required components, we have to create a settings array with the following information:
```php
$settings = [
    "domain" => "https://your-zitadel-instance.com",
    "serviceUserToken" => "Token of your service user",
    "userToken" => "User Token"
];
```
- **Domain:** The domain of your Zitadel instance without a slash at the end.
- **serviceUserToken:** The Token of a service User
<procedure title="Create a service user" id="create-service-user">
    <step>
        <p>Go to the user tab of your Zitadel instance. Then, click on <i>Machines</i> and <i>New</i>.</p>
    </step>
    <step>
        <p>Fill out the form and click "Create". <strong>IMPORTANT:&nbsp;</strong><i>Access Token Type</i> has to be <i>Bearer</i></p>
        <img src="create-service-user.png" alt="Create service user" border-effect="rounded"/>
    </step>
    <step>
        <p>Click on <i>Actions</i> and <i>Generate Client Secret</i>.</p>
    </step>
    <step>
        <p>The displayed client secret is the service user token.</p>
        <img src="service-user-client-secret.png" alt="Service User Client secret" border-effect="rounded"/>
    </step>
    <step>
        <p>Now we have to grant the permissions to the service user. Go to the Organization-Tab and click this Plus-Button:</p>
        <img src="add-user-to-organization.png" alt="" border-effect="rounded"/>
    </step>
    <step>
        <p>Select the service user from the list and check the permissions, the service user should have.</p>
</step>
</procedure>

- **userToken**: The token of a user. The user token is only required for a few operations.


You must always pass the settings array as a parameter to a class when you initialize it.
For example:
```php
use ZitadelPhpClient\User\Create;

$new_user = new Create($settings);
```