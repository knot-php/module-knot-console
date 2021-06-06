<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole\package;

use knotlib\kernel\Module\PackageInterface;
use knotphp\module\knotconsole\ArrayConfigShellRouterModule;

class KnotArrayConfigConsolePackage implements PackageInterface
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
                ArrayConfigShellRouterModule::class,
            ]);
    }
}