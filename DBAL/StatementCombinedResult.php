<?php

declare(strict_types=1);

namespace Lancoid\SymfonyJaegerBundle\DBAL;

use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;

interface StatementCombinedResult extends Statement, Result {}
