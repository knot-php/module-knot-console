<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole\Package;

use KnotLib\Kernel\Module\PackageInterface;
use KnotPhp\Module\KnotConsole\KnotShellResponderModule;
use KnotPhp\Module\KnotConsole\KnotShellRequestModule;
use KnotPhp\Module\KnotConsole\KnotShellResponseModule;
use KnotPhp\Module\KnotConsole\KnotShellRouterModule;

class KnotConsolePackage implements PackageInterface
{
    /**
     * Get package module list
     *
     * @return string[]
     */
    public static function getModuleList() : array
    {
        return [
            KnotShellRequestModule::class,
            KnotShellResponseModule::class,
            KnotShellResponderModule::class,
            KnotShellRouterModule::class,
        ];
    }
}