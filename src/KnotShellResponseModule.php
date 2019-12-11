<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole;

use Throwable;

use KnotLib\Console\Response\ShellResponse;
use KnotLib\Kernel\Kernel\ApplicationInterface;
use KnotLib\Kernel\Module\ComponentModule;
use KnotLib\Kernel\Exception\ModuleInstallationException;
use KnotLib\Kernel\Module\Components;
use KnotLib\Kernel\EventStream\Channels;
use KnotLib\Kernel\EventStream\Events;

class KnotShellResponseModule extends ComponentModule
{
    /**
     * Declare dependent on components
     *
     * @return array
     */
    public static function requiredComponents() : array
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
        return Components::RESPONSE;
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
        catch(Throwable $e)
        {
            throw new ModuleInstallationException(self::class, $e->getMessage(), 0, $e);
        }
    }
}