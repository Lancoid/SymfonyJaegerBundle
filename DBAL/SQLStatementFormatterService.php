<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

use Lancoid\SymfonyJaegerBundle\SymfonyJaegerBundle;

final class SQLStatementFormatterService implements SQLStatementFormatter
{
    public function formatForTracer(string $string): string
    {
        $beginningStatement = substr($string, 0, 32);
        $formattedStatement = $this->condenseStatement($string) ?? $beginningStatement;
        return SymfonyJaegerBundle::DBAL . ': ' . $formattedStatement;
    }

    public function buildSpanOrigin(string $string): string
    {
        $condensedStatement = $this->condenseStatement($string) ?? $string;
        $spacePosition = strpos($condensedStatement, ' ') ?: 6;
        return 'DBAL:' . strtolower(trim(substr($condensedStatement, 0, $spacePosition)));
    }

    private function condenseStatement(string $string): ?string
    {
        $matches = [];
        if (
            preg_match('/^(SELECT|DELETE).* FROM ([\S]+)/i', $string, $matches)
                || preg_match('/^(INSERT INTO|UPDATE) ([\S]+)/i', $string, $matches)
        ) {
            array_shift($matches);
            return implode(' ', $matches);
        }
        return null;
    }
}
