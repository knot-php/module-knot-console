<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Console\Request\ShellRequest;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Module\ModuleInterface;

class ShellRequestModule implements ModuleInterface
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
        ];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::REQUEST;
    }

    /**
     * Install module
     *
     * @param ApplicationInterface $app
     *
     * @throws ModuleInstallationException
     */
    public function install(ApplicationInterface $app)
    {
        try{
            $request = new ShellRequest($GLOBALS['argv'] ?? []);
            $app->request($request);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::REQUEST_ATTACHED, $request);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}