<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser;

use QueryBrowser\PersistentState;
use QueryBrowser\Result;
use QueryBrowser\Exception\InvalidIdentifierException;

/**
 * .
 */
class QueryBrowser extends PersistentState
{
    protected $driver;

    /**
     * [__construct description]
     * @param [type] $source [description]
     */
    public function __construct($driver)
    {
        $this->driver = $driver;

        $id = $driver->getId();
        if (empty($id)) {
            throw new InvalidIdentifierException('Identifier must not be empty.');
        }
        
        $this->setId('qb_' . $id);
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

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * [getPage description]
     * @return [type] [description]
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * [setPage description]
     * @param [type] $page [description]
     */
    public function setPage($page)
    {
        if (is_numeric($page) && $page > 0) {
            $this->page = $page;
        }
    }

    /**
     * [getPageSize description]
     * @return [type] [description]
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * [setPageSize description]
     * @param [type] $pageSize [description]
     */
    public function setPageSize($pageSize)
    {
        if (is_numeric($pageSize) && $pageSize >= 0) {
            $this->pageSize = $pageSize;
        }
    }

    public function getOrderBy()
    {
        return $this->orderBy;
    }

    public function setOrderBy($orderBy)
    {
        $this->orderBy = $orderBy;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }
    
    public function getGlobalSearch()
    {
        return $this->globalSearch;
    }

    public function setGlobalSearch($globalSearch)
    {
        $this->globalSearch = $globalSearch;
    }

    /**
     * .
     *
     * @return QueryBrowser\Result
     */
    public function execute()
    {
        $this->loadStateFromStorage();
        $this->setStateFromRequest();
        $this->saveStateToStorage();

        $this->driver->copyState($this);

        $results = $this->driver->getResults();
        $totalResults = $this->driver->getTotalResults();

        // somehow we have a page that is out of reach
        if (count($results) == 0 && $totalResults > 0 && $this->page > 1) {
            $_GET['qbId'] = $this->id;
            $_GET['qbPage'] = 1;
            return $this->execute();
        }

        $result = new Result($results, $totalResults);
        $result->copyState($this);
        return $result;
    }
}
