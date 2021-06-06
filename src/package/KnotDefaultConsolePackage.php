<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole\package;

use knotlib\kernel\module\PackageInterface;
use knotphp\module\knotconsole\ShellResponderModule;
use knotphp\module\knotconsole\ShellRequestModule;
use knotphp\module\knotconsole\ShellResponseModule;
use knotphp\module\knotconsole\ShellRoutingMiddlewareModule;

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