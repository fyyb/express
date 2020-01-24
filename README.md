# Fyyb Router

PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

## Getting started

### Installation

You must use Composer to install Fyyb classes (support classes for routing applications).

```
$ composer install
```

This will include in composer autoload all the necessary dependencies. Requires PHP 7.0 or later.

### .htaccess config

To use the fyyb-router, it is necessary to redirect your navigation to the route root file (index.php) where all traffic must be handled. The example below shows how:

```
  RewriteEngine On
  Options All -Indexes

  # ROUTER WWW Redirect.
  # RewriteCond %{HTTP_HOST} ^www\.(.*)$
  # RewriteRule ^ https://$1%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

  # ROUTER HTTPS Redirect
  # RewriteCond %{HTTP:X-Forwarded-Proto} !https
  # RewriteCond %{HTTPS} off
  # RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

  # ROUTER URL Rewrite
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_URI}::$1 ^(/.+)/(.*)::\2$
  RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

#### Routes

```php
<?php

require __DIR__ . "/../vendor/autoload.php";

use \Fyyb\Router\Router;
use \Fyyb\Http\Request;
use \Fyyb\Http\Response;

$app = Router::getInstance();

$app->get('/', function(Request $req, Response $res, $args) {
  echo 'Hello Fyyb';
});

$app->run();
```
