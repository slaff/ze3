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
use App\Entity\UserEntity;

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

    	// Sample: Authorization: Basic QWxhZGRpbjpPcGVuU2VzYW1l -> base64 -> user:password -
    	$authHeader = $request->getHeader("Authorization");
		if(!$authHeader) {
			$response = new JsonResponse([
					"auth" => false
			]);

			$response = $response->withStatus(401); // TODO...
			$response = $response->withHeader("WWW-Authenticate",'Basic realm="User Visible Realm"');

			return $response;
		}

		$authHeader = $authHeader[0];
		if(!preg_match('/^(\s*)Basic(\s+)(.*?)$/', $authHeader, $matches)) {
			$response = new JsonResponse([
					"auth" => false
			]);

			$response = $response->withStatus(401); // TODO...
			$response = $response->withHeader("WWW-Authenticate",'Basic realm="User Visible Realm"');

			return $response;
		}

		$encodedUserPassword = trim($matches[3]);
		$authString = base64_decode($encodedUserPassword);
		$data = preg_split("/:/", $authString, 2);

    	$user = $data[0];
    	$password = $data[1];

		$sql = "SELECT * FROM users WHERE username=? AND password=?";
		$resultSet = $this->db->query($sql, [$user, $password]);
// 		$count = $resultSet->count();
		$row = $resultSet->current();
		if($row['id']) {
			$authorized = true;

			$userEntity = new UserEntity();
			$userEntity->setId($row['id']);
			$userEntity->setName($row['username']);

			$request = $request->withAttribute(UserEntity::class, $userEntity);
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
