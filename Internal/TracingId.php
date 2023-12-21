<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Internal;

interface TracingId
{
    public function getAsString(): string;
}
