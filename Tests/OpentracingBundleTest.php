<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Tests;

use Lancoid\SymfonyJaegerBundle\DependencyInjection\PSR18CompilerPass;
use Lancoid\SymfonyJaegerBundle\SymfonyJaegerBundle;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OpentracingBundleTest extends TestCase
{
    use ProphecyTrait;

    private SymfonyJaegerBundle $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new SymfonyJaegerBundle();
    }

    public function testBuild(): void
    {
        $containerBuilder = $this->prophesize(ContainerBuilder::class);

        $containerBuilder
            ->addCompilerPass(Argument::type(PSR18CompilerPass::class))
            ->shouldBeCalled()
            ->willReturn($containerBuilder->reveal());

        $this->subject->build($containerBuilder->reveal());
    }
}
