<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

use Lancoid\SymfonyJaegerBundle\Internal\Constant;
use Lancoid\SymfonyJaegerBundle\Service\Tracing;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Exception;
use ReflectionException;
use ReflectionObject;

final class TracingEventListener
{
    private Tracing $tracing;
    private SpanFactory $spanFactory;

    public function __construct(
        Tracing $tracing,
        SpanFactory $spanFactory
    ) {
        $this->tracing = $tracing;
        $this->spanFactory = $spanFactory;
    }

    /**
     * @param ConnectionEventArgs $args
     *
     * @throws ReflectionException
     * @throws Exception
     */
    public function postConnect(ConnectionEventArgs $args): void
    {
        $connection = $args->getConnection();
        $params = $connection->getParams();

        $username = $params['user'] ?? null;

        $wrappedConnection = new TracingDriverConnection(
            $connection->getWrappedConnection(),
            $this->tracing,
            $this->spanFactory,
            $username
        );

        $reflectionObject = new ReflectionObject($connection);
        $property = $reflectionObject->getProperty('_conn');
        $property->setAccessible(true);
        $property->setValue($connection, $wrappedConnection);
        $property->setAccessible(false);

        // Account for already started transactions (used by autocommit)
        $inFlight = $connection->getTransactionNestingLevel();

        for ($i = 0; $i < $inFlight; $i++) {
            $this->tracing->startActiveSpan('DBAL: TRANSACTION');
            $this->spanFactory->addGeneralTags($username);
            $this->tracing->setTagOfActiveSpan(Constant::SPAN_ORIGIN, 'DBAL:transaction');
        }
    }
}
