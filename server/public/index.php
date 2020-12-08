<?php
require '../vendor/autoload.php';

use App\Control\Controller;
use App\Middleware\Game;
use App\Middleware\CORS;

$settings = require_once '../src/config/settings.php';
$errorHandlers = require_once '../src/config/errorHandlers.php';

$appConfig = array_merge($settings, $errorHandlers);

$container = new \Slim\Container($appConfig);
$app = new \Slim\App($container);

$app->post('/game[/]', Controller::class.':createGame')
	->add(CORS::class.':addCORSHeaders');

$app->post('/characters/goblin[/]', Controller::class.':createGoblin')
	->add(Game::class.':isGameCreated')
	->add(CORS::class.':addCORSHeaders');

$app->post('/characters/witch[/]', Controller::class.':createWitch')
	->add(Game::class.':isGameCreated')
	->add(CORS::class.':addCORSHeaders');

$app->post('/characters/orcq[/]', Controller::class.':createOrcq')
	->add(Game::class.':isGameCreated')
	->add(CORS::class.':addCORSHeaders');

$app->post('/play[/]', Controller::class.':doRound')
	->add(Game::class.':isGameCreated')
	->add(CORS::class.':addCORSHeaders');

$app->options('/{routes:.+}', function ($request, $response, $args) { return $response; })
    ->add(CORS::class.':addCORSHeaders');

$app->run();