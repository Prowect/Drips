<?php

use Prowect\Drips\Application;
use Prowect\Drips\Debug\ExceptionHandler;
use App\Http\Kernel as HttpKernel;
use App\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Http\Kernel as KernelContract;
use Illuminate\Contracts\Console\Kernel as ConsoleContract;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;

$app = new Application(DRIPS_DIRECTORY);

$app->singleton(KernelContract::class, HttpKernel::class);
$app->singleton(ConsoleContract::class, ConsoleKernel::class);
$app->singleton(ExceptionHandlerContract::class, ExceptionHandler::class);

return $app;