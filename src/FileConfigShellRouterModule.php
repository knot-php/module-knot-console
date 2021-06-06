<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole;

use Throwable;

use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\module\ModuleInterface;
use knotlib\kernel\filesystem\Dir;
use knotlib\kernel\filesystem\FileSystemInterface;
use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\console\router\Builder\PhpArrayShellRouterBuilder;
use knotlib\console\router\ShellDispatcherInterface;
use knotlib\console\router\ShellRouter;

use knotphp\module\knotconsole\exception\RoutingRuleConfigFileFormatException;
use knotphp\module\knotconsole\exception\RoutingRuleConfigNotFoundException;

final class FileConfigShellRouterModule implements ModuleInterface
{
    const ROUTING_RULE_CONFIG_FILE = 'route.config.php';

    /** @var ShellDispatcherInterface */
    private $dispatcher;

    /**
     * KnotShellRouterModule constructor.
     *
     * @param ShellDispatcherInterface|null $dispatcher
     */
    public function __construct(ShellDispatcherInterface $dispatcher = null)
    {
        $this->dispatcher = $dispatcher;
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
            (new PhpArrayShellRouterBuilder($router, $this->getRoutingRule($app->filesystem())))->build();

            $app->router($router);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::ROUTER_ATTACHED, $router);
        }
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }


    /**
     * Get routing rule
     *
     * @param FileSystemInterface $fs
     *
     * @return array
     *
     * @throws RoutingRuleConfigNotFoundException
     * @throws RoutingRuleConfigFileFormatException
     */
    private function getRoutingRule(FileSystemInterface $fs) : array
    {
        $routing_rule_config_file = $fs->getFile(Dir::CONFIG, self::ROUTING_RULE_CONFIG_FILE);
        if (!is_file($routing_rule_config_file)){
            throw new RoutingRuleConfigNotFoundException($routing_rule_config_file);
        }
        /** @noinspection PhpIncludeInspection */
        $ret = require($routing_rule_config_file);
        if (!is_array($ret)){
            throw new RoutingRuleConfigFileFormatException($routing_rule_config_file);
        }
        return $ret;
    }
}