<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Factory;

use OpenTracing\Tracer;

interface TracerFactory
{
    /**
     * @param mixed $samplerValue
     */
    public function create(
        string $projectName,
        string $agentHost,
        string $agentPort,
        string $samplerClass,
        $samplerValue
    ): Tracer;
}
