<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class AuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthMiddleware
    {
    	// get database from container
		$db = $container->get('db1');
        return new AuthMiddleware($db);
    }
}
