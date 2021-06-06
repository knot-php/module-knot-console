<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole;

use Throwable;

use knotlib\console\response\ShellResponse;
use knotlib\kernel\kernel\ApplicationInterface;
use knotlib\kernel\exception\ModuleInstallationException;
use knotlib\kernel\module\ComponentTypes;
use knotlib\kernel\eventstream\Channels;
use knotlib\kernel\eventstream\Events;
use knotlib\kernel\module\ModuleInterface;

class ShellResponseModule implements ModuleInterface
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
        return ComponentTypes::RESPONSE;
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
            $response = new ShellResponse();
            $app->response($response);

            // fire event
            $app->eventstream()->channel(Channels::SYSTEM)->push(Events::RESPONSE_ATTACHED, $response);
        }
        catch(Throwable $ex)
        {
            throw new ModuleInstallationException(self::class, $ex->getMessage(), $ex);
        }
    }
}