<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\Tests\Internal;

use Lancoid\SymfonyJaegerBundle\Internal\JaegerTracingId;
use Lancoid\SymfonyJaegerBundle\Service\Tracing;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class JaegerTracingIdTest extends TestCase
{
    use ProphecyTrait;

    private Tracing|ObjectProphecy $tracing;
    private JaegerTracingId $subject;

    public function setUp(): void
    {
        parent::setUp();

        $this->tracing = $this->prophesize(Tracing::class);
        $this->subject = new JaegerTracingId($this->tracing->reveal());
    }

    public function testGetAsStringSuccess(): void
    {
        $this->tracing
            ->injectTracingHeadersIntoCarrier([])
            ->shouldBeCalled()
            ->willReturn(['UBER-TRACE-ID' => 'abc:def:ghi:jkl']);

        self::assertSame('abc', $this->subject->getAsString());
    }

    public function testGetAsStringInvalidHeaderFormat(): void
    {
        $this->tracing
            ->injectTracingHeadersIntoCarrier([])
            ->shouldBeCalled()
            ->willReturn(['UBER-TRACE-ID' => 'abcdefghijkl']);

        self::assertSame('none', $this->subject->getAsString());
    }

    public function testGetAsStringNoHeader(): void
    {
        $this->tracing
            ->injectTracingHeadersIntoCarrier([])
            ->shouldBeCalled()
            ->willReturn([]);

        self::assertSame('none', $this->subject->getAsString());
    }
}
