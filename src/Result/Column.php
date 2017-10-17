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
    protected $textAlign = 'left'; // left, center, right
    protected $orderDirection; // null, 'asc', 'desc'
    protected $class;
    protected $callback;

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->name = $id;
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

        return $this;
    }

    public function isOrderable()
    {
        return $this->orderable;
    }

    public function setOrderable(bool $orderable)
    {
        $this->orderable = $orderable;

        return $this;
    }

    public function isVisible()
    {
        return $this->visible;
    }

    public function setVisible(bool $visible)
    {
        $this->visible = $visible;

        return $this;
    }

    public function getTextAlign()
    {
        return $this->textAlign;
    }

    public function setTextAlign($textAlign)
    {
        if (in_array($textAlign, ['left', 'center', 'right']) {
            $this->textAlign = $textAlign;
        } else {
            //@todo
            //throw new
        }
        return $this;
    }

    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    public function setOrderDirection($orderDirection)
    {
        if (in_array($orderDirection, [null, 'asc', 'desc']) {
            $this->orderDirection = $orderDirection;
        } else {
            //@todo
            //throw new
        }

        return $this;
    }
}
