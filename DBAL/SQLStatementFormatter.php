<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

interface SQLStatementFormatter
{
    public function formatForTracer(string $string): string;

    public function buildSpanOrigin(string $string): string;
}
