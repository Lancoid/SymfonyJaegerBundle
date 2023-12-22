<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DependencyInjection;

use Lancoid\SymfonyJaegerBundle\Internal\Decorator\PSR18ClientDecorator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class PSR18CompilerPass implements CompilerPassInterface
{
    public const TAG_PSR_18 = 'jaeger_opentracing.psr_18';

    public function process(ContainerBuilder $container): void
    {
        $psr18Clients = $container->findTaggedServiceIds(self::TAG_PSR_18);
        foreach (array_keys($psr18Clients) as $serviceId) {
            $foo = 'jaeger_opentracing.decorator.' . $serviceId;
            $container->register($foo, PSR18ClientDecorator::class)
                ->setDecoratedService($serviceId)
                ->setArgument(0, new Reference($foo . '.inner'))
                ->setPublic(false)
                ->setAutowired(true);
        }
    }
}
