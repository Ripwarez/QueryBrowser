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

use QueryBrowser\Result;
use QueryBrowser\Exception\InvalidIdentifierException;

/**
 * .
 */
class QueryBrowser
{
    /** @var string Unique id */
    protected $id;

    protected $driver;

    protected $page = 1;

    protected $globalSearch;

    protected $pageSize = 25;

    protected $orderBy;

    protected $orderDirection;


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
        $this->id = 'qb_' . $id;

        //$this->loadState();
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
     * [getOffset description]
     * @return [type] [description]
     */
    public function getOffset()
    {
        return ($this->page - 1) * $this->pageSize;
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

    public function getGlobalSearch()
    {
        return $this->globalSearch;
    }

    public function setGlobalSearch($globalSearch)
    {
        $this->globalSearch = $globalSearch;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }

    /**
     * .
     *
     * @return QueryBrowser\Result
     */
    public function execute()
    {
        /*
        if ( Input::get('qbId') == $this->id )
        {
            $this->setStateFromGET();
        }

        // search
        if ( !empty($this->searchString) )
        {
            $this->applySearch();
        }

        // set total rows
        // this also sets total pages
        $this->setTotalRows($this->calculateTotalRows());

        $this->getResults();

        $this->saveState();
*/
        $this->loadStateFromRequest();

        $results = $this->driver->getResults($this->getOffset(), $this->pageSize, $this->globalSearch, $this->orderBy, $this->orderDirection);
        $totalResults = $this->driver->getTotalResults();

        return new Result($this, $results, $totalResults);
    }

    protected function loadStateFromRequest()
    {
        $this->loadState($_POST);
        $this->loadState($_GET);
    }

    protected function loadState($array)
    {
        if (isset($array['qbId']) && $array['qbId'] == $this->id) {
           $this->setPage($array['qbPage']);
           $this->setPageSize($array['qbPageSize']);
           $this->setGlobalSearch($array['qbGlobalSearch']);
           $this->setOrderBy($array['qbOrderBy']);
           $this->setOrderDirection($array['qbOrderDirection']);
        
           return true;
        }

        return false;
    }

    protected function saveState()
    {
    }
}
