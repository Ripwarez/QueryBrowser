<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace QueryBrowser\Result;

/**
 *
 */
class Column
{
    protected $id;
    protected $name;
    protected $visible = true;
    protected $orderable = true;
    protected $searchable = true;
    protected $textAlign  = 'left'; // left, middle, right
    protected $orderDirection; // null, 'asc', 'desc'
    protected $callback;

    public function __construct($id, $sequence)
    {
        $this->id = $id;
        $this->name = $id;
        $this->sequence = $sequence;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function isOrderable()
    {
        return $this->orderable;
    }

    public function setOrderable($orderable)
    {
        $this->orderable = $orderable;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    public function getTextAlign()
    {
        return $this->textAlign;
    }

    public function setTextAlign($textAlign)
    {
        $this->textAlign = $textAlign;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function setOrderDirection($orderDirection)
    {
        $this->orderDirection = $orderDirection;
    }
}
