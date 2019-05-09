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
    	$authorized = false;

		// get the matched route
    	$result = $request->getAttribute(RouteResult::class);
    	$params = $request->getQueryParams();

    	// TODO: create table users(uniqueid, username, password)
    	// TODO: Get the username and password from Basic Auth....
    	$user = $params['user'];
    	$password = $params['password'];

		$sql = "SELECT * FROM users WHERE username=? AND password=?";
		$resultSet = $this->db->query($sql, [$user, $password]);
// 		$count = $resultSet->count();
		$row = $resultSet->current();
		if($row['id']) {
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
