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

use QueryBrowser\State;

/**
 * .
 */
class ArrayDriver extends State implements DriverInterface
{
    /**
     * [$array description]
     * @var [type]
     */
    protected $data;

    /*
     * @var  string  order column that can be accessed from the static sortResults function
     */
    protected static $_orderBy;

    /*
     * @var  string  order direction that can be accessed from the static sortResults function
     */
    protected static $_orderDirection;

    /**
     * [__construct description]
     * @param [type] $array [description]
     * @param [type] $id    [description]
     */
    public function __construct($data, $id = null)
    {
        $this->data = $data;
        $this->id = md5(implode('-', array_keys($data)));
    }

    /**
     * Gets the id.
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
    public function getResults()
    {
        if (!empty($this->globalSearch)) {
            $this->applyGlobalSearch($this->globalSearch);
        }

        // sort
        if (!empty($this->orderBy) && !empty($this->orderDirection)) {
            self::$_orderBy = $this->orderBy;
            self::$_orderDirection = $this->orderDirection;
            usort($this->data, 'self::sortResults');
        }
    
        $offset = $this->getOffset();
        if ($offset > 0 || $this->pageSize > 0) {
            return array_slice($this->data, $offset, $this->pageSize);
        }

        return $this->data;
    }

    public function getTotalResults()
    {
        return count($this->data);
    }

    /**
     * [applySearch description]
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
     * sort
     * @return int
     */
    protected static function sortResults($a, $b)
    {
        $a = strtolower($a[self::$_orderBy]);
        $b = strtolower($b[self::$_orderBy]);

        if (self::$_orderDirection == 'asc') {
            return ($a < $b) ? -1 : 1;
        } else {
            return ($a > $b) ? -1 : 1;
        }

        return 0;
    }
}
