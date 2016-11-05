<?php

define('LARAVEL_START', microtime(true));

define('DRIPS_DIRECTORY', realpath(__DIR__ . DIRECTORY_SEPARATOR . '..'));
define('COMPOSER_AUTOLOAD', DRIPS_DIRECTORY . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
define('DRIPS_SETUP', __DIR__ . DIRECTORY_SEPARATOR . 'setup' . DIRECTORY_SEPARATOR . 'index.php');

/*
|--------------------------------------------------------------------------
| Drips Installer
|--------------------------------------------------------------------------
|
| Display installer/setup if needed.
|
*/

if(php_sapi_name() != 'cli' && (!file_exists(COMPOSER_AUTOLOAD) || !file_exists(DRIPS_DIRECTORY . DIRECTORY_SEPARATOR .'.env'))){
	require_once DRIPS_SETUP;
	exit;
}

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require_once COMPOSER_AUTOLOAD;

/*
|--------------------------------------------------------------------------
| Include The Compiled Class File
|--------------------------------------------------------------------------
|
| To dramatically increase your application's performance, you may use a
| compiled class file which contains all of the classes commonly used
| by a request. The Artisan "optimize" is used to create this file.
|
*/

$compiledPath = __DIR__.'/cache/compiled.php';

if (file_exists($compiledPath)) {
    require $compiledPath;
}
