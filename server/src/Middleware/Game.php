<?php
namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;
use App\Utils\JSON;

class Game
{
	public function isGameCreated(Request $request, Response $response, callable $next)
	{
		if (!file_exists(__DIR__.'/../../characters.json'))
			return JSON::errorResponse($response, 400, "Il n'y a pas de partie en cours.");

		return $next($request, $response);
	}
}