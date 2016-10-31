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

/**
 * .
 */
class State
{
    /** @var string Unique id */
    protected $id;

    protected $page = 1;

    /* set to zero for unlimited */
    protected $pageSize = 25;

    protected $orderBy;

    protected $orderDirection;

    protected $globalSearch;

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
     * [getOffset description]
     * @return [type] [description]
     */
    protected function getOffset()
    {
        return ($this->page - 1) * $this->pageSize;
    }

    protected function copyState($source)
    {
        $this->id = $source->id;
        $this->page = $source->page;
        $this->pageSize = $source->pageSize;
        $this->orderBy = $source->orderBy;
        $this->orderDirection = $source->orderDirection;
        $this->globalSearch = $source->globalSearch;
    }
}
