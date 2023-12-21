<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Factory;

use RuntimeException;

interface AgentHostResolver
{
    /**
     * @throws RuntimeException
     */
    public function ensureAgentHostIsResolvable(string $agentHost): void;
}
