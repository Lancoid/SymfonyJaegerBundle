<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Tests\Factory;

use Lancoid\SymfonyJaegerBundle\Factory\JaegerStaticConfigFactory;
use PHPUnit\Framework\TestCase;

class JaegerStaticConfigFactoryTest extends TestCase
{
    private JaegerStaticConfigFactory $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->subject = new JaegerStaticConfigFactory();
    }

    public function testCreate(): void
    {
        $config = $this->subject->create();
        self::assertNotNull($config);
    }
}
