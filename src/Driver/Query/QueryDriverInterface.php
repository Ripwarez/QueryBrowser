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

/**
 * Interface implemented by QueryBrowser\QueryDriver classes.
 *
 * The query driver handles fetching the results from the source based
 * on the supplied state.
 */
interface QueryDriverInterface
{
    /**
     * Generate an unique id based on source/query.
     *
     * The generated id remains the same if the source is unchanged
     *
     * @return string
     */
    public function generateId(): string;

    /**
     * Get the last order by.
     *
     * @return OrderBy
     */
    public function getOrderBy(): OrderBy;

    /**
     * Get the results from the source/query.
     *
     * @return array Associative array having the column names as array keys.
     */
    public function getResults(OrderBy $orderBy, SearchManager $searchManager, int $offset, int $limit): array;

    /**
     * Get the number of total available results.
     *
     * @return int
     */
    public function getTotalResults(OrderBy $orderBy, SearchManager $searchManager): int;
}
