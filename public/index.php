<?php

use Dotenv\Dotenv;
use WouterDeSchuyter\WhenLol\Application\Http\Application;

// Application directory
define('APP_DIR', dirname(__DIR__));

// Include composer autoloader
require_once (APP_DIR . '/vendor/autoload.php');

// Init dotenv
(new Dotenv(APP_DIR))->overload();

// Init http app
(new Application())->run();
