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
 * Simple search.
 *
 */
class Search
{
    /**
     * Search types
     */
    const TYPE_EQUALS = 0;
    const TYPE_NOT_EQUALS = 1;
    const TYPE_GREATER_THAN = 2;
    const TYPE_GREATER_THAN_OR_EQUAL = 3;
    const TYPE_LESS_THAN = 4;
    const TYPE_LESS_THAN_OR_EQUAL = 5;
    const TYPE_STARTS_WITH = 6;
    const TYPE_ENDS_WITH = 7;
    const TYPE_SUBSTRING = 8;

    /**
     *
     *
     * @var string
     */
    protected $query;

    /**
     *
     *
     * @var int
     */
    protected $type;

    /**
     *
     *
     * @param string $query [description]
     * @param int    $type  [description]
     *
     * @return void
     */
    public function __construct(string $query, int $type)
    {
        $this->setQuery($query);
        $this->setType($type);
    }

    /**
     * Get query
     *
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set query
     *
     * @param string $query
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setQuery(string $query)
    {
        if (0 === mb_strlen($query)) {
            throw new InvalidArgumentException('Query cannot be empty.');
        }

        $this->query = $query;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param int $type
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setType(int $type)
    {
        if ($type < 0 || $type > 8) {
            throw new InvalidArgumentException('Invalid type.');
        }

        $this->type = $type;

        return $this;
    }

    /**
     * Convert this object to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'query' => $this->query,
            'type'  => $this->type,
        ];
    }
}
