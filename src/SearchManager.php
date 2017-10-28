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

namespace PaulHekkema\QueryBrowser;

/**
 *
 */
class SearchManager
{
    /**
     *
     *
     * @var array
     */
    protected $globalSearch = null;

    /**
     *
     *
     * @var array
     */
    protected $columnSearch = [];

    /**
     *
     *
     * @param string $query [description]
     * @param int    $type  [description]
     * @param string $column
     *
     * @return void
     */
    public function addSearch(string $query, int $type = Search::TYPE_EQUALS, string $column = null)
    {
        // todo, validate column

        $search = new Search($query, $type);

        if (null === $column) {
            $this->globalSearch = $search;
        } else {
            $this->columnSearch[$column] = $search;
        }
    }

    /**
     * [getGlobalSearch description]
     *
     * @return array|null
     */
    public function getGlobalSearch()
    {
        return $this->globalSearch;
    }

    /**
     * [getGlobalSearch description]
     *
     * @return array
     */
    public function getColumnSearch()
    {
        return $this->columnSearch;
    }

    /**
     *
     *
     * @return bool
     */
    public function isEmpty()
    {
        return (null === $this->globalSearch && 0 === count($this->columnSearch));
    }

    /**
     * Convert this object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $data = [];

        if (null !== $this->globalSearch) {
            $data['global'] = $this->globalSearch->toArray();
        }

        foreach ($this->columnSearch as $column => $search) {
            $data['column'][$column][] = $search->toArray();
        }

        return $data;
    }
}
