<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Lancoid\SymfonyJaegerBundle\DependencyInjection\PSR18CompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SymfonyJaegerBundle extends Bundle
{
    public const DBAL = 'DBAL';

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new PSR18CompilerPass());
    }
}
