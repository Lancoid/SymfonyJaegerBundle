<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

use Lancoid\SymfonyJaegerBundle\Internal\Constant;
use Lancoid\SymfonyJaegerBundle\Service\Tracing;
use Doctrine\DBAL\Driver\Connection as DBALDriverConnection;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
final class TracingDriverConnection implements DBALDriverConnection, WrappingDriverConnection
{
    private DBALDriverConnection $decoratedConnection;
    private Tracing $tracing;
    private SpanFactory $spanFactory;
    private ?string $username;

    public function __construct(
        DBALDriverConnection $decoratedConnection,
        Tracing $tracing,
        SpanFactory $spanFactory,
        ?string $username
    ) {
        $this->decoratedConnection = $decoratedConnection;
        $this->tracing = $tracing;
        $this->spanFactory = $spanFactory;
        $this->username = $username;
    }

    /**
     * @param string $sql
     *
     * @return TracingStatement
     * @throws Exception
     */
    public function prepare(string $sql): Statement
    {
        $statement = $this->decoratedConnection->prepare($sql);
        return new TracingStatement($statement, $this->spanFactory, $sql, $this->username);
    }

    /**
     * @param string $sql
     *
     * @return TracingStatement
     * @throws Exception
     */
    public function query(string $sql): Result
    {
        $args = func_get_args();
        $parameters = array_slice($args, 1);
        $this->spanFactory->beforeOperation($args[0]);
        $result = $this->decoratedConnection->query(...$args);
        $this->spanFactory->afterOperation($args[0], $parameters, $this->username, (int) $result->rowCount());
        return new TracingStatement($result, $this->spanFactory, $args[0], $this->username);
    }

    /**
     * @param string $value
     * @param int $type
     * @return string
     */
    public function quote($value, $type = 2): string // we do not want a hard dependency on PDO
    {
        return $this->decoratedConnection->quote($value, $type);
    }

    /**
     * @inheritDoc
     */
    public function exec($sql): int
    {
        $this->spanFactory->beforeOperation($sql);
        $result = $this->decoratedConnection->exec($sql);
        $this->spanFactory->afterOperation($sql, [], $this->username, (int) $result);
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function lastInsertId($name = null)
    {
        return $this->decoratedConnection->lastInsertId($name);
    }

    /**
     * @inheritDoc
     */
    public function beginTransaction()
    {
        $this->tracing->startActiveSpan('DBAL: TRANSACTION');
        $this->spanFactory->addGeneralTags($this->username);
        $this->tracing->setTagOfActiveSpan(Constant::SPAN_ORIGIN, 'DBAL:transaction');
        $result = $this->decoratedConnection->beginTransaction();
        return is_bool($result) === true ? $result : true;
    }

    /**
     * @inheritDoc
     */
    public function commit()
    {
        $result = $this->decoratedConnection->commit();
        $this->tracing->setTagOfActiveSpan('db.transaction.end', 'commit');
        $this->tracing->finishActiveSpan();
        return is_bool($result) === true ? $result : true;
    }

    /**
     * @inheritDoc
     */
    public function rollBack()
    {
        $result = $this->decoratedConnection->rollBack();
        $this->tracing->setTagOfActiveSpan('db.transaction.end', 'rollBack');
        $this->tracing->finishActiveSpan();
        return is_bool($result) === true ? $result : true;
    }

    /**
     * @inheritDoc
     */
    public function errorCode()
    {
        return $this->decoratedConnection->errorCode();
    }

    /**
     * @return array<mixed>
     */
    public function errorInfo(): array
    {
        return $this->decoratedConnection->errorInfo();
    }

    public function getWrappedConnection(): DBALDriverConnection
    {
        return $this->decoratedConnection;
    }
}
