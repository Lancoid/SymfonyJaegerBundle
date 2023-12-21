<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Internal;

interface Persistence
{
    public function flush(): void;
}
