<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole;

use Throwable;

use knotlib\console\responder\ShellResponder;
use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\kernel\module\ModuleInterface;

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
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }
}