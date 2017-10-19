<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/querybrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace QueryBrowser\QueryDriver;

use QueryBrowser\OrderBy;
use QueryBrowser\SearchFilter;

/**
 * Interface implemented by QueryBrowser\QueryDriver classes.
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
    public function generateId();

    /**
     * Get the results from the source/query.
     *
     * @return array Associative array having the column names as array keys.
     */
    public function getResults(OrderBy $orderBy, SearchFilter $searchFilter, int $offset, int $limit);

    /**
     * Get the number of total available results.
     *
     * @return int
     */
    public function getTotalResults(OrderBy $orderBy, SearchFilter $searchFilter);
}
