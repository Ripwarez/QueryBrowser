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

use QueryBrowser\State;

/**
 * .
 */
class PersistentState extends State
{
    protected function getState()
    {
        return [
            'qbId'             => $this->id,
            'qbPage'           => $this->page,
            'qbPageSize'       => $this->pageSize,
            'qbGlobalSearch'   => $this->globalSearch,
            'qbOrderBy'        => $this->orderBy,
            'qbOrderDirection' => $this->orderDirection
        ];
    }

    protected function setState($state)
    {
        if (isset($state['qbId']) && $state['qbId'] == $this->id) {
            if (isset($state['qbPage'])) {
                $this->setPage($state['qbPage']);
            }

            if (isset($state['qbPageSize'])) {
                $this->setPageSize($state['qbPageSize']);
            }

            if (isset($state['qbGlobalSearch'])) {
                $this->setGlobalSearch($state['qbGlobalSearch']);
            }

            if (isset($state['qbOrderBy'])) {
                $this->setOrderBy($state['qbOrderBy']);
            }
            
            if (isset($state['qbOrderDirection'])) {
                $this->setOrderDirection($state['qbOrderDirection']);
            }
        }
    }

    protected function setStateFromRequest()
    {
        if (isset($_POST)) {
            $this->setState($_POST);
        }
        if (isset($_GET)) {
            $this->setState($_GET);
        }
    }
    
    protected function loadStateFromStorage()
    {
        if (isset($_COOKIE[$this->id])) {
            $this->setState(unserialize($_COOKIE[$this->id]));
        }
    }

    protected function saveStateToStorage()
    {
        setcookie($this->id, serialize($this->getState()));
    }
}
