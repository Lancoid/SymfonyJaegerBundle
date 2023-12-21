<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Internal\Decorator;

interface RequestSpanning
{
    public function start(string $requestMethod, string $requestUrl): void;

    public function finish(int $responseStatusCode): void;
}
