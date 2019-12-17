<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole\Package;

use KnotLib\Kernel\Module\PackageInterface;
use KnotPhp\Module\KnotConsole\ShellResponderModule;
use KnotPhp\Module\KnotConsole\ShellRequestModule;
use KnotPhp\Module\KnotConsole\ShellResponseModule;
use KnotPhp\Module\KnotConsole\FileConfigShellRouterModule;
use KnotPhp\Module\KnotConsole\ShellRoutingMiddlewareModule;

class KnotDefaultConsolePackage implements PackageInterface
{
    /**
     * Get package module list
     *
     * @return string[]
     */
    public static function getModuleList() : array
    {
        return [
            ShellRequestModule::class,
            ShellResponseModule::class,
            ShellResponderModule::class,
            ShellRoutingMiddlewareModule::class,
        ];
    }
}