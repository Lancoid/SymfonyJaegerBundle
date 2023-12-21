<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Factory;

use Jaeger\Config;

interface JaegerConfigFactory
{
    public function create(): Config;
}
