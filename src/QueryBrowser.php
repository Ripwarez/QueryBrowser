<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace QueryBrowser;

use QueryBrowser\Exception\InvalidIdentifierException;
use QueryBrowser\OrderBy;
use QueryBrowser\QueryDriver\QueryDriverInterface;
use QueryBrowser\RequestDriver\RequestDriverInterface;
use QueryBrowser\StorageDriver\StorageDriverInterface;
use QueryBrowser\SearchFilter;

/**
 * QueryBrowser
 */
class QueryBrowser implements \Serializable
{
    /**
     * Unique ID
     *
     * @var string
     */
    protected $id;

    /**
     *
     *
     * @var QueryDriverInterface
     */
    protected $queryDriver;

    /**
     *
     *
     * @var RequestDriverInterface
     */
    protected $requestDriver;

    /**
     *
     *
     * @var StorageDriverInterface
     */
    protected $storageDriver;

    /**
     * Pagenumber
     *
     * @var int
     */
    protected $page = 1;

    /**
     * Pagesize
     *
     * Set to zero for no limit
     *
     * @var int
     */
    protected $pageSize = 25;

    /**
     * @TODO
     *
     * @var OrderBy
     */
    protected $orderBy;

    /**
     * @TODO
     *
     * @var SearchFilter
     */
    protected $searchFilter;

    /**
     * Constructor
     *
     * @param string $id
     * @param QueryDriverInterface $queryDriver
     *
     * @return void
     *
     * @throws InvalidIdentifierException When id is empty or invalid
     */
    public function __construct(
        string $id,
        QueryDriverInterface $queryDriver,
        RequestDriverInterface $requestDriver,
        StorageDriverInterface $storageDriver
    )
    {
        if ('' === $id) {
            throw new InvalidIdentifierException('Identifier can not be empty.');
        }

        if (0 === preg_match('/[a-zA-Z0-9]+/', $id)) {
            throw new InvalidIdentifierException(
                sprintf('Identifier can only contain alfanumeric characters (%s).', $id)
            );
        }

        // always prefix the id
        $this->id = 'qb_' . $id;

        $this->queryDriver = $queryDriver;
        $this->requestDriver = $requestDriver;
        $this->storageDriver = $storageDriver;

        $this->orderBy = new OrderBy();
        $this->searchFilter = new SearchFilter();
    }

    /**
     * Get the driver
     *
     * @return QueryDriverInterface
     */
    public function getQueryDriver()
    {
        return $this->queryDriver;
    }

    /**
     * Get the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get page
     *
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set page
     *
     * @param int $page
     *
     * @return self
     */
    public function setPage(int $page)
    {
        if ($page > 0) {
            $this->page = $page;
        }

        return $this;
    }

    /**
     * Get pagesize
     *
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Set pagesize
     *
     * @param int $pageSize
     *
     * @return self
     */
    public function setPageSize(int $pageSize)
    {
        if ($pageSize >= 0) {
            $this->pageSize = $pageSize;
        }

        return $this;
    }

    /**
     * Get the results from the driver and add this to a new Result.
     *
     * @return Result
     */
    public function execute()
    {
        // load state from storage
        $this->loadStateFromStorage();

        // get state from request
        $this->getStateFromRequest();

        // get the results from the driver
        $results = $this->queryDriver->getResults(
            $this->orderBy,
            $this->searchFilter,
            $this->getOffset(),
            $this->pageSize
        );

        // get total number if results from the driver
        $totalResults = $this->queryDriver->getTotalResults($this->orderBy, $this->searchFilter);

        // somehow we have a page that is out of reach
        if (count($results) === 0 && $totalResults > 0 && $this->page > 1) {
            $this->requestDriver->set('qbId', $this->id);
            $this->requestDriver->set('qbPage', 1);

            return $this->execute();
        }

        // save state to storage
        $this->saveStateToStorage();

        $result = new Result($results, $totalResults, $this);

        return $result;
    }

    /**
     * Get offset
     *
     * @return int
     */
    public function getOffset()
    {
        return ($this->page - 1) * $this->pageSize;
    }

    /**
     * Serialize
     *
     * @return array
     */
    public function serialize()
    {
        return serialize([
            'id'           => $this->id,
            'page'         => $this->page,
            'pageSize'     => $this->pageSize,
            'orderBy'      => $this->orderBy,
            'searchFilter' => $this->searchFilter,
        ]);
    }

    /**
     * Unserialize
     *
     * @param  string $data
     *
     * @return void
     */
    public function unserialize($data)
    {
        $data = unserialize($data);

        if (is_array($data)) {
            $this->loadStateFromArray($data);
        }
    }

    /**
     * Load the state from an array.
     *
     * @param array $data
     *
     * @return void
     */
    public function loadStateFromArray(array $data)
    {
        if (isset($data['id']) && $data['id'] === $this->id) {
            if (isset($data['page'])) {
                $this->setPage($data['page']);
            }

            if (isset($data['pageSize'])) {
                $this->setPageSize($data['pageSize']);
            }

            /*
            if (isset($data['globalSearch'])) {
                $this->setGlobalSearch($data['globalSearch']);
            }

            if (isset($data['orderBy'])) {
                $this->setOrderBy($data['orderBy']);
            }
            */
        }
    }

    /**
     * Load state from the storage driver.
     *
     * @return void
     */
    protected function loadStateFromStorage()
    {
        $data = $this->storageDriver->get($this->id);

        if (null !== $data) {
            $this->unserialize($data);
        }
    }

    /**
     * Get state from the request driver.
     *
     * @return void
     */
    protected function getStateFromRequest()
    {
        $data = $this->requestDriver->getAll($this->id);

        if (null !== $data) {
            $this->loadStateFromArray($data);
        }
    }

    /**
     * Save state using the storage driver.
     *
     * @return void
     */
    protected function saveStateToStorage()
    {
        return $this->storageDriver->set($this->id, $this->serialize());
    }

}
