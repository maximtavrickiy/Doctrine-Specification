<?php

/**
 * This file is part of the Happyr Doctrine Specification package.
 *
 * (c) Tobias Nyholm <tobias@happyr.com>
 *     Kacper Gunia <kacper@gunia.me>
 *     Peter Gribanov <info@peter-gribanov.ru>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Happyr\DoctrineSpecification\Operand;

use Doctrine\ORM\QueryBuilder;
use Happyr\DoctrineSpecification\ValueConverter;

class Value implements Operand
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var int|string|null
     */
    private $valueType;

    /**
     * @param mixed           $value
     * @param int|string|null $valueType \PDO::PARAM_* or \Doctrine\DBAL\Types\Type::* constant
     */
    public function __construct($value, $valueType = null)
    {
        $this->value = $value;
        $this->valueType = $valueType;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $dqlAlias
     *
     * @return string
     */
    public function transform(QueryBuilder $qb, $dqlAlias)
    {
        $paramName = sprintf('comparison_%d', $qb->getParameters()->count());
        $value = ValueConverter::convertToDatabaseValue($this->value, $qb);
        $qb->setParameter($paramName, $value, $this->valueType);

        return sprintf(':%s', $paramName);
    }
}
