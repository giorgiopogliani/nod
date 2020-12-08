<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new LaravelZero\Framework\Application(
    dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Set the correct path for the environment file
|--------------------------------------------------------------------------
|
| If the current directory has a .env file then we will use that instead
| of the global one, otherwise the one under the user homepage.
|
 */

$app->instance(
    'path.env',
    getenv('HOME')  . DIRECTORY_SEPARATOR . '.config'  . DIRECTORY_SEPARATOR . 'nod'
);

if (! file_exists(dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env')) {
    $app->useEnvironmentPath(getenv('HOME')  . DIRECTORY_SEPARATOR . '.config'  . DIRECTORY_SEPARATOR . 'nod');
}

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    LaravelZero\Framework\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Illuminate\Foundation\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
