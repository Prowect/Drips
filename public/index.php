<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('BOOTSTRAP_DIRECTORY', __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap');

require_once BOOTSTRAP_DIRECTORY . DIRECTORY_SEPARATOR . 'autoload.php';

$app = require_once BOOTSTRAP_DIRECTORY . DIRECTORY_SEPARATOR . 'app.php';

$kernel = $app->make(Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);