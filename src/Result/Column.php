<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/QueryBrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace PaulHekkema\QueryBrowser\Result;

use PaulHekkema\QueryBrowser\Exception\InvalidArgumentException;

/**
 * Column
 */
class Column
{
    /**
     *
     *
     * @var string
     */
    protected $id;

    /**
     *
     *
     * @var string
     */
    protected $name;

    /**
     *
     *
     * @var int
     */
    protected $sequence;

    /**
     *
     *
     * @var bool
     */
    protected $visible = true;

    /**
     *
     *
     * @var bool
     */
    protected $orderable = true;

    /**
     *
     *
     * @var bool
     */
    protected $searchable = true;

    /**
     *
     *
     * @var string
     */
    protected $textAlign = 'left';

    /**
     *
     *
     * @var string
     */
    protected $orderDirection = null;

    /**
     *
     *
     * @var string
     */
    protected $class;

    /**
     * Constructor
     *
     * @param string $id
     * @param int    $sequence
     *
     * @return void
     */
    public function __construct(string $id, int $sequence)
    {
        $this->id = $id;
        $this->name = $id;

        $this->sequence = $sequence * 100;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get sequence
     *
     * @return int
     */
    public function getSequence()
    {
        return $this->name;
    }

    /**
     * Set sequence
     *
     * The columns from the result are multiplied by a factor of 100 so it's
     * easier to add column between them. So the first column has sequence 0 and
     * the second 200, third 300, etc.
     *
     * @param int $sequence
     *
     * @return self
     */
    public function setSequence(int $sequence)
    {
        $this->sequence = $sequence;

        return $this;
    }

    /**
     * Is visible or not
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->visible;
    }

    /**
     * Set visible
     *
     * @param bool $visible
     *
     * @return self
     */
    public function setVisible(bool $visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Can be ordered or not
     *
     * @return bool
     */
    public function isOrderable()
    {
        return $this->orderable;
    }

    /**
     * Set orderable
     *
     * @param bool $orderable
     *
     * @return self
     */
    public function setOrderable(bool $orderable)
    {
        $this->orderable = $orderable;

        return $this;
    }

    /**
     * Get text alignment
     *
     * @return string
     */
    public function getTextAlign()
    {
        return $this->textAlign;
    }

    /**
     * Set text alignment
     *
     * @param string $textAlign
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setTextAlign(string $textAlign)
    {
        if (!in_array($textAlign, ['left', 'center', 'right'])) {
            throw new InvalidArgumentException("The value must be 'left', 'center' or 'right'.");
        }

        $this->textAlign = $textAlign;

        return $this;
    }

    /**
     * Get order direction
     *
     * @return string
     */
    public function getOrderDirection()
    {
        return $this->orderDirection;
    }

    /**
     * Set order direction
     *
     * @param string $orderDirection
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setOrderDirection(string $orderDirection)
    {
        if (!in_array($orderDirection, ['', 'asc', 'desc'])) {
            throw new InvalidArgumentException("The value must be '', 'asc' or 'desc'.");
        }

        $this->orderDirection = $orderDirection;

        return $this;
    }
}
