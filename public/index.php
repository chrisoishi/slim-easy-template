<?php
use Slim\Factory\AppFactory;
use DI\Container;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../configs/routes.php';
require __DIR__ . '/../configs/error_handler.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$container = new Container();
AppFactory::setContainer($container);

$app = AppFactory::create();



routes($app);
errorHandlingSetup($app);



$app->run();