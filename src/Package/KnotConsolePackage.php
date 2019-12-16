<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole\Package;

use KnotLib\Kernel\Module\PackageInterface;
use KnotPhp\Module\KnotConsole\KnotShellResponderModule;
use KnotPhp\Module\KnotPipeline\KnotPipelineModule;
use KnotPhp\Module\Stk2kEventStream\Stk2kEventStreamModule;
use KnotPhp\Module\KnotConsole\KnotShellRequestModule;
use KnotPhp\Module\KnotConsole\KnotShellResponseModule;

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
            KnotPipelineModule::class,
            Stk2kEventStreamModule::class,
            KnotShellRequestModule::class,
            KnotShellResponseModule::class,
            KnotShellResponderModule::class,
        ];
    }
}