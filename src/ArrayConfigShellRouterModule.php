<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole;

use Throwable;

use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\console\router\builder\PhpArrayShellRouterBuilder;
use knotlib\console\router\ShellDispatcherInterface;
use knotlib\console\router\ShellRouter;
use knotlib\kernel\module\ModuleInterface;

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
     * @param array|null $routing_rule
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
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }
}