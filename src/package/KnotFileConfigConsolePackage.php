<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole\package;

use knotlib\kernel\module\PackageInterface;
use knotphp\module\knotconsole\FileConfigShellRouterModule;

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