<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser\Driver;

/**
 * .
 */
class ArrayDriver implements DriverInterface
{
    /**
     * [$id description]
     * @var [type]
     */
    protected $id;

    /**
     * [$array description]
     * @var [type]
     */
    protected $array;

    /*
     * @var  string  order column that can be accessed from the static sortResults function
     */
    protected static $orderBy;

    /*
     * @var  string  order direction that can be accessed from the static sortResults function
     */
    protected static $orderDirection;

    /**
     * [__construct description]
     * @param [type] $array [description]
     * @param [type] $id    [description]
     */
    public function __construct($array, $id = null)
    {
        $this->array = $array;
        $this->id = 'array';
    }

    /**
     * Get the id.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return void
     */
    public function getResults($offset, $limit, $globalSearch, $orderBy, $orderDirection)
    {
        if (!empty($globalSearch)) {
            $this->applyGlobalSearch($globalSearch);
        }

        // sort
        if (!empty($orderBy) && !empty($orderDirection)) {
            self::$orderBy= $orderBy;
            self::$orderDirection= $orderDirection;
            usort($this->array, 'self::sortResults');
        }
    
        if ($offset > 0 || $limit > 0) {
            return array_slice($this->array, $offset, $limit);
        }

        return $this->array;
    }

    public function getTotalResults()
    {
        return count($this->array);
    }


    /**
     * [applySearch description]
     * @return void
     */
    protected function applyGlobalSearch($searchString)
    {
        foreach ($this->array as $k => $row) {
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
                unset($this->array[$k]);
            }
        }
    }

    /**
     * sort
     * @return int
     */
    protected static function sortResults($a, $b)
    {
        $a = strtolower($a[self::$orderBy]);
        $b = strtolower($b[self::$orderBy]);

        if (self::$orderDirection == 'asc') {
            return ($a < $b) ? -1 : 1;
        } else {
            return ($a > $b) ? -1 : 1;
        }

        return 0;
    }
}
