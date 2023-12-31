<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Internal;

class Constant
{
    /**
     * The tag name of tags containing the origin of the span. Each OpentracingBundle plugin is required to
     * provide this tag for spans the plugin started itself.
     *
     * The convention of the tag content is: <plugin:detail>
     * Examples:
     *  - core:command
     *  - dbal:transaction
     */
    public const SPAN_ORIGIN = 'symfony-jaeger-bundle.span-origin';
}
