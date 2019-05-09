<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Expressive\Router\RouteResult;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;

class AuthMiddleware implements MiddlewareInterface
{
	const KEY = "letmein";

	/**
	 * @var AdapterInterface
	 */
	private $db;

	public function __construct(AdapterInterface $db)
	{
		$this->db = $db;
	}

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler) : ResponseInterface
    {
		// get the matched route
    	$result = $request->getAttribute(RouteResult::class);


		// TODO: Check if should be authenticated

    	$authorized = false;
    	// check if there is query param "letmein" and letmein==1 then allow.
    	$params = $request->getQueryParams();
    	if(isset($params[self::KEY]) && $params[self::KEY] == 1) {
    		$authorized = true;
    	}

    	if(!$authorized) {
    		$response = new JsonResponse([
    				"auth" => false
    		]);

    		$response = $response->withStatus(401);
    		$response = $response->withHeader("X-Inpeco-Auth","Denied");

    		return $response;
    	}

        $response = $handler->handle($request);

        return $response;
    }
}
