# Fyyb Express

> PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

## Getting started

### Installation

Assuming you already have composer knowledge, create a directory to store your application and make it your working directory.

```
$ composer require fyyb/express
```

This will include in composer autoload all the necessary dependencies.
Requires PHP 7.0 or later!

### Usage example

After installing, create the .htacces, config.php and index.php files at the root of your project.

To keep your code separate from the configuration files, we suggest that you use a folder/file structure similar to the one below:

```
   .
   ├─ App/
   │    └── ...
   ├─ vendor/
   │    └── ...
   ├─ .htaccess
   ├─ config.php
   ├─ index.php
   └─ composer.json
```

Don't forget to add your application's namespace in the autoload of the composer.json.

```json
    "autoload": {
        "psr-4": {
            "App\\": "App/"
        }
    },
```

### .htaccess

To use fyyb/express, it is necessary to redirect all navigation to the root file (index.php), where all traffic must be handled.
The example below shows how:

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php [QSA]
```

### config.php

File where the variables/constants of the project will be defined.
If your project directory is different from the root, configure the BASE_DIR constant for the correct operation in identifying the routes.

```php
<?php

    const BASE_DIR = '/ path / to / project /';

    ...

```

## Hello world

See how easy it is, in your index.php, insert the code below:

```php
<?php

    require __DIR__ . "/config.php";
    require __DIR__ . "/vendor/autoload.php";

    use Fyyb\Router;
    use Fyyb\Request;
    use Fyyb\Response;

    $app = Router::getInstance();

    $app->get('/', function(Request $req, Response $res) {
        $res->send('Hello World!');
    });

    $app->run();
```

This app responds with "Hello, world!" for requests to the root URL (/) or route. For all other paths, it will respond with a 404 not found.

## Simple routing

Routing refers to determining how an application responds to a client request to a particular endpoint, which is a URI (or path) and a specific HTTP request method (GET, POST, and so on).

Each route can have one or more handler functions, which are executed when the route is matched.

Route definition takes the following structure:

```
    $app->METHOD(PATH, HANDLER)
```

Where:

-   \$app is an instance of fyyb/express.
-   METHOD is an HTTP request method, in lowercase.
-   PATH is a path on the server.
-   HANDLER is the function executed when the route is matched.

The following examples illustrate defining simple routes.
Respond with Hello World! on the homepage:

```php
    $app->get('/', function (Request $req, Response $res) {
        $res->send('Hello World!');
    });
```

Respond to POST request on the root route (/), the application’s home page:

```php
    $app->post('/', function (Request $req, Response $res) {
        $res->send('Got a POST request');
    });
```

Respond to PUT request on the root route (/), the application’s home page:

```php
    $app->put('/', function (Request $req, Response $res) {
        $res->send('Got a PUT request');
    });
```

Respond to DELETE request on the root route (/), the application’s home page:

```php
    $app->delete('/', function (Request $req, Response $res) {
        $res->send('Got a DELETE request');
    });
```

Respond to a GET, POST, PUT and DELETE request to the /user route:

```php
    $app->any('/user', function (Request $req, Response $res) {
        $res->send('Got a GET, POST, PUT and DELETE request at /user');
    });
```

Or customize the methods with map function

```
    $app->map([METHOD], PATH, HANDLER)
```

Where:

-   \$app is an instance of fyyb/express.
-   map is a routing function.
-   [METHOD] is an array of HTTP request methods.
-   PATH is a path on the server.
-   HANDLER is the function performed when the route is matched.

Respond to a GET and POST request to the /test route:

```php
    $app->map(['GET', 'POST'], '/test', function (Request $req, Response $res) {
        $res->send('Got a GET and POST request at /test');
    });
```
