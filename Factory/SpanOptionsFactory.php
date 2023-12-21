<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Factory;

use Symfony\Component\HttpFoundation\Request;

interface SpanOptionsFactory
{
    /**
     * @return array<string,mixed>
     */
    public function createSpanOptions(Request $request = null): array;
}
