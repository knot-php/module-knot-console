<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\Module\ModuleInterface;
use KnotLib\Kernel\FileSystem\Dir;
use KnotLib\Kernel\FileSystem\FileSystemInterface;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Console\Router\Builder\PhpArrayShellRouterBuilder;
use KnotLib\Console\Router\ShellDispatcherInterface;
use KnotLib\Console\Router\ShellRouter;

use KnotPhp\Module\KnotConsole\Exception\RoutingRuleConfigFileFormatException;
use KnotPhp\Module\KnotConsole\Exception\RoutingRuleConfigNotFoundException;

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
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
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