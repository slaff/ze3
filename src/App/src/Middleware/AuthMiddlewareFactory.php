<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class AuthMiddlewareFactory
{
    public function __invoke(ContainerInterface $container) : AuthMiddleware
    {
    	// TODO: get database from container

    	$db = 0;

        return new AuthMiddleware($db);
    }
}
