<?php
declare(strict_types=1);

namespace knotphp\module\knotconsole\Exception;

use Throwable;

final class RoutingRuleConfigFileFormatException extends KnotConsoleModuleException
{
    /**
     * RoutingRuleConfigFileFormatException constructor.
     *
     * @param string $routing_rule_config_file
     * @param int $code
     * @param Throwable|null $prev
     */
    public function __construct( string $routing_rule_config_file, int $code = 0, Throwable $prev = null )
    {
        parent::__construct("Routing rule config file is invalid: {$routing_rule_config_file}", $code, $prev);
    }
}