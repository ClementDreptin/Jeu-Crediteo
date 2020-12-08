<?php
namespace App\Middleware;

use Slim\Http\Request;
use Slim\Http\Response;

class CORS
{
	public function addCORSHeaders(Request $request, Response $response, callable $next)
	{
		// Pour simplifier les choses pour le développement j'ai autorisé toutes les origines,
		// je sais bien que c'est à bannir en production.
        return $next($request, $response)
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
    }
}