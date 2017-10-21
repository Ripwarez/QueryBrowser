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

namespace QueryBrowser\Driver\Query;

use QueryBrowser\OrderBy;
use QueryBrowser\FilterManager;

/**
 * QueryDriver for an array.
 */
class ArrayQueryDriver implements QueryDriverInterface
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
    public function getOrderBy()
    {
        return new OrderBy();
    }

    /**
     * {@inheritDoc}
     */
    public function getResults(OrderBy $orderBy, FilterManager $filterManager, int $offset, int $limit)
    {
        // if (!empty($this->globalSearch)) {
        //     $this->applyGlobalSearch($this->globalSearch);
        // }

        // sort
        if (!$orderBy->isEmpty()) {
            self::$sortOrderBy = $orderBy->getField();
            self::$sortOrderDirection = $orderBy->getDirection();
            usort($this->data, 'self::sortResults');
        }

        if ($offset > 0 || $limit > 0) {
            return array_slice($this->data, $offset, $limit);
        }

        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalResults(OrderBy $orderBy, FilterManager $filterManager)
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

                if (false !== stripos($field, $searchString)) {
                    $found = true;
                    break;
                }
            }

            if (false === $found) {
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
        $a = $a[self::$sortOrderBy];
        $b = $b[self::$sortOrderBy];

        if ('asc' === self::$sortOrderDirection) {
            return ($a < $b) ? -1 : 1;
        } else {
            return ($a > $b) ? -1 : 1;
        }

        return 0;
    }
}
