<?php

use Slim\Http\Response;
use Slim\Http\Request;
use App\Utils\JSON;

return [
	'notFoundHandler' => function ($container) {
		return function (Request $request, Response $response) use ($container)
		{
			return JSON::errorResponse($response, 400, "Erreur dans le format de la requête.");
		};
	},
	'notAllowedHandler' => function ($container) {
		return function (Request $request, Response $response, $allowed_methods) use ($container)
		{
			return JSON::errorResponse($response, 405, "Méthode non-autorisée. Méthodes autorisées : ".implode(', ', $allowed_methods));
		};
	},
	'phpErrorHandler' => function ($container) {
		return function (Request $request, Response $response, \Error $exception) use ($container)
		{
			return JSON::errorResponse($response, 500, "Erreur interne au serveur.");
		};
	}
];