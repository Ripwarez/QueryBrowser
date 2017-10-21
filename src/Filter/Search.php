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

namespace QueryBrowser\Filter;

/**
 * Simple search.
 *
 * Currently only supports a global search which means all the
 * columns are searched.
 */
class Search
{
    /**
     *
     *
     * @var string
     */
    protected $query;

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set query
     *
     * @param string $query
     *
     * @return self
     */
    public function setQuery(string $query)
    {
        $this->query = $query;

        return $this;
    }
}
