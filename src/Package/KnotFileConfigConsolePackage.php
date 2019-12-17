<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole\Package;

use KnotLib\Kernel\Module\PackageInterface;
use KnotPhp\Module\KnotConsole\FileConfigShellRouterModule;

class KnotFileConfigConsolePackage implements PackageInterface
{
    /**
     * Get package module list
     *
     * @return string[]
     */
    public static function getModuleList() : array
    {
        return array_merge(
            KnotDefaultConsolePackage::getModuleList(),
            [
                FileConfigShellRouterModule::class,
            ]);
    }
}