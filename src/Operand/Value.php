<?php

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
     * @var integer|null
     */
    private $valueType;

    /**
     * @param mixed        $value
     * @param integer|null $valueType PDO::PARAM_* or \Doctrine\DBAL\Types\Type::* constant
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
        $qb->setParameter($paramName, ValueConverter::convertToDatabaseValue($this->value, $qb), $this->valueType);

        return sprintf(':%s', $paramName);
    }
}
