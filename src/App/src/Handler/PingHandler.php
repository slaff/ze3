<?php

declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\JsonResponse;

use function time;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Router\RouteResult;

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
    	$result = $request->getAttribute(RouteResult::class);
    	$name = $result->getMatchedRouteName();

        return new JsonResponse([
        		'ack' => time(),
        		'route-name' => $name,
        ]);
    }
}
