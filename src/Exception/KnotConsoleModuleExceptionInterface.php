<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole\Exception;

use KnotLib\Exception\KnotPhpExceptionInterface;
use KnotLib\Exception\Runtime\RuntimeExceptionInterface;

interface KnotConsoleModuleExceptionInterface extends KnotPhpExceptionInterface, RuntimeExceptionInterface
{
}