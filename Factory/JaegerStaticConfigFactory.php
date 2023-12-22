<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Factory;

use Jaeger\Config;
use RuntimeException;

final class JaegerStaticConfigFactory implements JaegerConfigFactory
{
    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function create(): Config
    {
        $config = Config::getInstance();

        if (!$config) {
            throw new RuntimeException('Config not found.');
        }

        return $config;
    }
}
