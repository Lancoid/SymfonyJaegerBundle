<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

use Lancoid\SymfonyJaegerBundle\Service\Tracing;
use Doctrine\Bundle\DoctrineBundle\ConnectionFactory as DoctrineConnectionFactory;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Events;

final class TracingConnectionFactory
{
    private DoctrineConnectionFactory $connectionFactory;
    private TracingEventListener $eventListener;

    public function __construct(
        DoctrineConnectionFactory $connectionFactory,
        Tracing $tracing,
        SpanFactory $spanFactory
    ) {
        $this->connectionFactory = $connectionFactory;
        $this->eventListener = new TracingEventListener($tracing, $spanFactory);
    }

    /**
     * @param array<string,mixed>  $params
     * @param Configuration|null   $config
     * @param EventManager|null    $eventManager
     * @param array<string,string> $mappingTypes
     *
     * @return Connection
     * @throws Exception
     */
    public function createConnection(
        array $params,
        Configuration $config = null,
        EventManager $eventManager = null,
        array $mappingTypes = []
    ): Connection {
        $connection = $this->connectionFactory->createConnection($params, $config, $eventManager, $mappingTypes);
        $connection->getEventManager()->addEventListener(Events::postConnect, $this->eventListener);
        return $connection;
    }
}
