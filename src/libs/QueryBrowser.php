<?php

namespace QueryBrowser;

class QueryBrowser
{
    /** @var string Unique id */
    protected $id;

    protected $source;

    protected $query;

    protected $offset = 0;

    protected $searchText = '';

    protected $pageSize = 20;

    protected $orderColumn;

    protected $orderDirection = 'asc';


    public function construct($source)
    {
        $this->source = $source;

        if ( empty($id) )
        {
            return;
        }

        $this->loadState();

        $this->id = 'qb_' . $id;
    }

    public function setPage($page)
    {
        if (!is_numeric($page) || $page < 0) {
            $this->setOffset(0);
        }

        $this->setOffset($page * $this->pageSize);
    }

    public function getPage()
    {
        if ($this->offset == 0) {
            return 1;
        }

        return ($this->offset / $this->pageSize) + 1;
    }

    public function setOffset($offset)
    {
        if (!is_numeric($offset) || $offset < 0) {
            $this->offset = 0
        }

        $this->offset = $offset;
    }

    public function setPageSize($pageSize)
    {
        if (!is_numeric($pageSize)) {
            $this->pageSize = $pageSize;
        }
    }

    public function save()
    {

    }

    public function load()
    {

    }

    /**
     * .
     *
     * @return QueryBrowserResult
     */
    public function execute()
    {
        if (is_null($this->id)) {
            return;
        }

        if (is_null($this->query)) {
            return;
        }

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

        return new QueryBrowserResult($this, $this->columns, $this->results);
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
     * Set the id.
     *
     * @param   string  $id
     * @return  void
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}