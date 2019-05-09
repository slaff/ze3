<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;
use Zend\Expressive\Router\RouterInterface;

class PingHandler implements RequestHandlerInterface
{
	/** @var Router\RouterInterface */
	private $router;

	public function __construct(RouterInterface $router)
	{
		$this->router = $router;
	}

    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
    	// TODO: get the router result <- what was the matched route to come here
    	// TODO: from the router result  get the name of the route that matched
		$name = "Fake";

        return new JsonResponse([
        			'ack' => time(),
        			'route-name' => $name,
        ]);
    }
}
