<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Console\Router\Builder\PhpArrayShellRouterBuilder;
use KnotLib\Console\Router\ShellDispatcherInterface;
use KnotLib\Console\Router\ShellRouter;
use KnotLib\Kernel\Module\ModuleInterface;

final class ArrayConfigShellRouterModule implements ModuleInterface
{
    /** @var ShellDispatcherInterface */
    private $dispatcher;

    /** @var array */
    private $routing_rule;

    /**
     * KnotShellRouterModule constructor.
     *
     * @param ShellDispatcherInterface|null $dispatcher
     * @param array $routing_rule
     */
    public function __construct(ShellDispatcherInterface $dispatcher = null, array $routing_rule = null)
    {
        $this->dispatcher = $dispatcher;
        $this->routing_rule = $routing_rule ?? [];
    }

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
        return ComponentTypes::ROUTER;
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
            $router = new ShellRouter($this->dispatcher);
            (new PhpArrayShellRouterBuilder($router, $this->routing_rule))->build();

            $app->router($router);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::ROUTER_ATTACHED, $router);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}