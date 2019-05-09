<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

class AuthMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
    	$response = new JsonResponse([
    					"auth" => false
    				]);

    	$response = $response->withStatus(401);

    	// Task: add special header X-Inpeco-Auth: Denied

//     	return $response;
//     	//
//         $response = $handler->handle($request);
//         return $response;
    }
}
