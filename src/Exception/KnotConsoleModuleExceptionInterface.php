<?php
declare(strict_types=1);

namespace KnotPhp\Module\KnotConsole\Exception;

use KnotLib\Exception\KnotPhpExceptionInterface;
use KnotLib\Exception\Runtime\RuntimeExceptionInterface;

interface KnotConsoleModuleExceptionInterface extends KnotPhpExceptionInterface, RuntimeExceptionInterface
{
}