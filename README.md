# Fyyb Express

> PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

## Getting started

### Installation

You must use Composer to install Fyyb classes (support classes for routing applications).

```
$ composer require fyyb/express
```

This will include in composer autoload all the necessary dependencies.  
Requires PHP 7.0 or later.

## Usage example

After installing fyyb-router, create the .htacces, settings.php and index.php files at the root of your project.

To keep your code separate from the configuration files, we suggest that you use a folder/file structure similar to the one below:

```
project/
   ├─ App/
   │   ├── Controllers/
   │   ├── Middlewares/
   │   ├── Models/
   │   ├── Routes/
   │   ├── Views/
   │   └── ...
   │
   ├─ vendor/
   │   └── ...
   │
   ├─ .htaccess
   ├─ settings.php
   ├─ index.php
   └─ composer.json
```

Don't forget to add your application's namespace in the autoload of the composer.json file.

```json
    "autoload": {
        "psr-4": {
            "App\\": "App/"
        }
    },
```

### .htaccess

To use the fyyb-router, it is necessary to redirect your navigation to the route root file (index.php) where all traffic must be handled.
The example below shows how:

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php [QSA]
```

### settings.php

File where the variables / constants of the project will be defined.  
Easily switch between production and development environments using the constant ENVIRONMENT. If you are in a development environment, set the BASE_DIR constant for the correct operation in identifying the routes.

```php
<?php

    /************************************
     * Environment settings
     ***********************************/

    // Development Environment
    const ENVIRONMENT = 'dev';
    const BASE_DIR = '/path/to/project/';

    // Production Environment
    // const ENVIRONMENT = 'prod';

```

### index.php

```php
<?php

    require __DIR__ . "./settings.php";
    require __DIR__ . "./vendor/autoload.php";

    use \Fyyb\Router;
    use \Fyyb\Request;
    use \Fyyb\Response;

    $app = Router::getInstance();

    $app->get('/', function(Request $req, Response $res, $args) {
        echo 'Hello Fyyb';
    });

    $app->run();
```
