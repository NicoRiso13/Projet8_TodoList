<?php

use App\AppKernel;
use Composer\Autoload\ClassLoader;
use Symfony\Component\HttpFoundation\Request;

/** @var ClassLoader $loader */
$loader = require __DIR__ . '/../app/autoload.php';
include_once __DIR__ . '/../var/bootstrap.php.cache';

$kernel = new AppKernel('prod', false);



// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
