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

        $result = new Result($results, $totalResults);
        $result->copyState($this);
        return $result;
    }
}
