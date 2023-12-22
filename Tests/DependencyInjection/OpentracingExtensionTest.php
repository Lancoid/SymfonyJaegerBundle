<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Tests\DependencyInjection;

use Exception;
use Lancoid\SymfonyJaegerBundle\DependencyInjection\OpentracingExtension;
use Lancoid\SymfonyJaegerBundle\Factory\TracerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OpentracingExtensionTest extends TestCase
{
    private OpentracingExtension $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new OpentracingExtension();
    }

    /**
     * @throws Exception
     */
    public function testLoad(): void
    {
        $container = new ContainerBuilder();
        $container->setParameter('kernel.debug', 'test');
        $container->setParameter('kernel.project_dir', '/some/path/to/random-project');

        $this->subject->load([], $container);

        self::assertArrayHasKey(TracerFactory::class, $container->getDefinitions());
    }
}
