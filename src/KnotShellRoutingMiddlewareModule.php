<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentModule;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Console\Middleware\ShellRoutingMiddleware;

final class KnotShellRoutingMiddlewareModule extends ComponentModule
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
    {
        return [
            Components::EVENTSTREAM,
            Components::PIPELINE,
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return Components::MODULE;
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
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}