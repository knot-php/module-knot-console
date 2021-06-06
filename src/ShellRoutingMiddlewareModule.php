<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole;

use Throwable;

use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\console\middleware\ShellRoutingMiddleware;
use knotlib\kernel\module\ModuleInterface;

final class ShellRoutingMiddlewareModule implements ModuleInterface
{
    /**
     * Declare dependency on another modules
     *
     * @return array
     */
    public static function requiredModules() : array
    {
        return [];
    }

    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponentTypes() : array
    {
        return [
            ComponentTypes::EVENTSTREAM,
            ComponentTypes::PIPELINE,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::MIDDLEWARE;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $middleware = new ShellRoutingMiddleware($app);
            $app->pipeline()->push($middleware);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::PIPELINE_MIDDLEWARE_PUSHED, $middleware);
        }
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }
}