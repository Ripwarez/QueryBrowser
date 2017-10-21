<?php

/**
 * QueryBrowser
 *
 * @link      https://gitlab.kapma.nl/paulhekkema/querybrowser
 * @license   MIT (see LICENSE for details)
 * @author    Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace QueryBrowser;

use QueryBrowser\Exception\InvalidArgumentException;

/**
 * The last order by from the source or the order by manually set by the user.
 */
class OrderBy
{
    /**
     *
     *
     * @var string
     */
    protected $field;

    /**
     *
     *
     * @var string
     */
    protected $direction;

    /**
     *
     *
     * @param string $field
     * @param string $direction
     *
     * @return void
     */
    public function __construct(string $field = '', string $direction = '')
    {
        $this->field = $field;
        $this->setDirection($direction);
    }

    /**
     *
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     *
     *
     * @param string $field
     *
     * @return self
     */
    public function setField(string $field)
    {
        $this->field = $field;

        return $this;
    }

    /**
     *
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     *
     *
     * @param string $direction
     *s
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setDirection(string $direction)
    {
        if (false === in_array($direction, ['', 'asc', 'desc'])) {
            throw new InvalidArgumentException("The value must be '', 'asc' or 'desc'.");
        }

        $this->direction = $direction;

        return $this;
    }

    /**
     *
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ('' === $this->field || '' === $this->direction);
    }

    /**
     * Convert this object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'field' => $this->field,
            'direction'  => $this->direction,
        ];
    }
}
