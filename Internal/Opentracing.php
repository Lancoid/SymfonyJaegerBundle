<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Internal;

use OpenTracing\Tracer;

interface Opentracing
{
    public function getTracerInstance(): Tracer;
}
