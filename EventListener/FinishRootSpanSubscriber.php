<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\EventListener;

use Lancoid\SymfonyJaegerBundle\Internal\Persistence;
use Lancoid\SymfonyJaegerBundle\Service\Tracing;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;

final class FinishRootSpanSubscriber implements EventSubscriberInterface
{
    private Tracing $tracing;
    private Persistence $persistence;

    public function __construct(Tracing $tracing, Persistence $persistence)
    {
        $this->tracing = $tracing;
        $this->persistence = $persistence;
    }

    /**
     * @return array<string,array<int|string>>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.finish_request' => ['onFinishRequest', -16],
            'kernel.terminate' => ['onTerminate', -16],
        ];
    }

    public function onFinishRequest(KernelEvent $event): void
    {
        if (method_exists($event, 'isMainRequest')) {
            if (!$event->isMainRequest()) {
                return;
            }
        } elseif (method_exists($event, 'isMasterRequest')) {
            if (!$event->isMasterRequest()) {
                return;
            }
        }

        $this->tracing->finishActiveSpan();
    }

    public function onTerminate(KernelEvent $event): void
    {
        if (method_exists($event, 'isMainRequest')) {
            if (!$event->isMainRequest()) {
                return;
            }
        } elseif (method_exists($event, 'isMasterRequest')) {
            if (!$event->isMasterRequest()) {
                return;
            }
        }

        $this->persistence->flush();
    }
}
