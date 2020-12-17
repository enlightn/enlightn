<?php


namespace Enlightn\Enlightn\Tests\Analyzers\Concerns;

use Enlightn\Enlightn\Inspection\Reflector;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Session\Middleware\StartSession;

trait InteractsWithMiddleware
{
    protected function registerStatefulGlobalMiddleware()
    {
        $this->app->make(Kernel::class)->pushMiddleware(StartSession::class);
        $this->app->make(Kernel::class)->pushMiddleware(AddQueuedCookiesToResponse::class);
    }

    /**
     * @param string $middlewareGroup
     * @param string $middlewareClass
     * @throws \ReflectionException
     * @see \Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware::getGlobalMiddleware()
     */
    protected function registerGroupMiddleware(string $middlewareGroup, string $middlewareClass)
    {
        // mirror mirror on the wall, why is PHP the greatest of all?
        $middlewareGroups = Reflector::get($kernel = $this->app->make(Kernel::class), 'middlewareGroups');

        if (!isset($middlewareGroups[$middlewareGroup])) {
            $middlewareGroups[$middlewareGroup] = [$middlewareClass];
        } else {
            $middlewareGroups[$middlewareGroup][] = $middlewareClass;
        }

        Reflector::set($kernel, 'middlewareGroups', $middlewareGroups);
    }

    /**
     * @param string $alias
     * @param string $middlewareClass
     * @throws \ReflectionException
     * @see \Enlightn\Enlightn\Analyzers\Concerns\AnalyzesMiddleware::getGlobalMiddleware()
     */
    protected function registerRouteMiddlewareAlias(string $alias, string $middlewareClass)
    {
        $routeMiddleware = Reflector::get($kernel = $this->app->make(Kernel::class), 'routeMiddleware');

        $routeMiddleware[$alias] = $middlewareClass;

        Reflector::set($kernel, 'routeMiddleware', $routeMiddleware);
    }
}
