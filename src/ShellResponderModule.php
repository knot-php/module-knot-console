<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Console\Responder\ShellResponder;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\ComponentTypes;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;
use KnotLib\Kernel\Module\ModuleInterface;

class ShellResponderModule implements ModuleInterface
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
        return [];
    }

    /**
     * Declare component type of this module
     *
     * @return string
     */
    public static function declareComponentType() : string
    {
        return ComponentTypes::RESPONDER;
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
            $responder = new ShellResponder();
            $app->responder($responder);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::RESPONDER_ATTACHED, $responder);
        }
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}