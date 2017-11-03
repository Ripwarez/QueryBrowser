<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/QueryBrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Hekkema\QueryBrowser\Driver\Query;

use Hekkema\QueryBrowser\OrderBy;
use Hekkema\QueryBrowser\SearchManager;
use Hekkema\QueryBrowser\Search;
use Doctrine\ORM\QueryBuilder;

/**
 * QueryDriver for Doctrine\ORM\QueryBuilder.
 */
class DoctrineORMQueryDriver implements QueryDriverInterface
{
    /**
     * Source data
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * Construct a new ArrayDriver.
     *
     * @param array $data
     *
     * @return void
     */
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * {@inheritDoc}
     */
    public function generateId(): string
    {
        return md5($this->queryBuilder->getDQL());
    }

    /**
     * @inheritDoc
     */
    public function getOrderBy(): OrderBy
    {
        $orderBy = new OrderBy();

        $parts = end($this->queryBuilder->getDQLParts()['orderBy'])->getParts();
        $lastPart = end($parts);
        if (false !== $lastPart) {
        	list($field, $direction) = explode(' ', $lastPart);
        	$orderBy->setField($field);
        	$orderBy->setDirection($direction);
        }

        return $orderBy;
    }

    /**
     * @inheritDoc
     */
    public function getResults(OrderBy $orderBy, SearchManager $searchManager, int $offset, int $limit): array
    {
        $queryBuilder = clone $this->queryBuilder;

        /* search
        if (!$searchManager->isEmpty()) {
            $this->applyGlobalSearch($searchManager->getGlobalSearch());
        }
        */

        // sort
        if (!$orderBy->isEmpty()) {
        	// todo, will probably fail anytime soon since we assume
        	// it's part of the root alias.
            $queryBuilder->orderBy($queryBuilder->getRootAlias().'.'.$orderBy->getField(), $orderBy->getDirection());
        }

        // offset & limit
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($limit);

        return $queryBuilder->getQuery()->getArrayResult();
    }

    /**
     * @inheritDoc
     */
    public function getTotalResults(OrderBy $orderBy, SearchManager $searchManager): int
    {
        $queryBuilder = clone $this->queryBuilder;
        $queryBuilder->select($queryBuilder->expr()->count($queryBuilder->getRootAlias()));
        $queryBuilder->setFirstResult(0);
        //$queryBuilder->setMaxResults(0);

        return (int) $queryBuilder->getQuery()->getSingleScalarResult();
    }
}
