# Fyyb Router

PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

```php
<?php

use \Fyyb\Router\Router;
use \Fyyb\Http\Request;
use \Fyyb\Http\Response;

$app = Router::getInstance();

$app->get('/', \App\Controllers\ExController::class.":index");

$app->run();
```

## Installation

You must use Composer to install Fyyb classes (support classes for routing applications).

```
$ composer install
```

This will include in composer autoload all the necessary dependencies. Requires PHP 7.0 or later.
