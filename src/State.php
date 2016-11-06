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
