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

namespace QueryBrowser\QueryDriver;

use QueryBrowser\QueryDriver\QueryDriverInterface;
use QueryBrowser\OrderBy;
use QueryBrowser\SearchFilter;

/**
 * QueryDriver for an array.
 */
class ArrayDriver implements QueryDriverInterface
{
    /**
     * Source data
     *
     * @var array
     */
    protected $data;

    /**
     * Order column that can be accessed from the static sortResults function
     *
     * @var string
     */
    protected static $sortOrderBy;

    /**
     * Order direction that can be accessed from the static sortResults function
     *
     * @var string
     */
    protected static $sortOrderDirection;

    /**
     * Construct a new ArrayDriver.
     *
     * @param array $data
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function generateId()
    {
        $keys = implode('-', array_keys($this->data));

        return md5($keys);
    }

    /**
     * {@inheritDoc}
     */
    public function getResults(OrderBy $orderBy, SearchFilter $searchFilter, int $offset, int $limit)
    {
        /*
        if (!empty($this->globalSearch)) {
            $this->applyGlobalSearch($this->globalSearch);
        }

        // sort
        if (!empty($this->orderBy) && !empty($this->orderDirection)) {
            self::$sortOrderBy = $this->orderBy;
            self::$sortOrderDirection = $this->orderDirection;
            usort($this->data, 'self::sortResults');
        }
        */

        if ($offset > 0 || $limit > 0) {
            return array_slice($this->data, $offset, $limit);
        }

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalResults(OrderBy $orderBy, SearchFilter $searchFilter)
    {
        return count($this->data);
    }

    /**
     * [applySearch description]
     *
     * @return void
     */
    protected function applyGlobalSearch($searchString)
    {
        foreach ($this->data as $k => $row) {
            $found = false;
            foreach ($row as $field) {
                if (is_array($field) || is_object($field)) {
                    $field = print_r($field, true);
                }

                if (stripos($field, $searchString) !== false) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                unset($this->data[$k]);
            }
        }
    }

    /**
     * Sort results based on the static variables in this class.
     *
     * @return int
     */
    protected static function sortResults($a, $b)
    {
        $a = strtolower($a[self::$sortOrderBy]);
        $b = strtolower($b[self::$sortOrderDirection]);

        if (self::$_orderDirection == 'asc') {
            return ($a < $b) ? -1 : 1;
        } else {
            return ($a > $b) ? -1 : 1;
        }

        return 0;
    }
}
