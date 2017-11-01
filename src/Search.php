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

namespace Hekkema\QueryBrowser;

use Hekkema\QueryBrowser\Exception\InvalidArgumentException;

/**
 * Simple search.
 *
 */
class Search
{
    /**
     * Search comparison operators
     */
    const OPERATOR_EQUALS = 0;
    const OPERATOR_NOT_EQUALS = 1;
    const OPERATOR_GREATER_THAN = 2;
    const OPERATOR_GREATER_THAN_OR_EQUAL = 3;
    const OPERATOR_LESS_THAN = 4;
    const OPERATOR_LESS_THAN_OR_EQUAL = 5;
    const OPERATOR_STARTS_WITH = 6;
    const OPERATOR_ENDS_WITH = 7;
    const OPERATOR_SUBSTRING = 8;

    /**
     * Search sensitivity
     */
    const CASE_INSENSITIVE = 0;
    const CASE_SENSITIVE = 1;

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
    protected $operator;

    /**
     *
     *
     * @var int
     */
    protected $caseSensitivity;

    /**
     *
     *
     * @param string $query [description]
     * @param int    $operator  [description]
     *
     * @return void
     */
    public function __construct(string $query, int $operator, int $caseSensitivity)
    {
        $this->setQuery($query);
        $this->setOperator($operator);
        $this->setSensitivity($caseSensitivity);
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
     * Get operator
     *
     * @return int
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set operator
     *
     * @param int $operator
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setOperator(int $operator)
    {
        if ($operator < 0 || $operator > 8) {
            throw new InvalidArgumentException('Invalid operator.');
        }

        $this->operator = $operator;

        return $this;
    }

    /**
     * Get case sensitivity
     *
     * @return int
     */
    public function getCaseSensitivity()
    {
        return $this->caseSensitivity;
    }

    /**
     * Is case sensitive
     *
     * @return bool
     */
    public function isCaseSensitive()
    {
        return $this->caseSensitivity === self::CASE_SENSITIVE;
    }


    /**
     * Set case sensitivity
     *
     * @param int $caseSensitivity
     *
     * @return self
     *
     * @throws InvalidArgumentException
     */
    public function setSensitivity(int $caseSensitivity)
    {
        if ($caseSensitivity < 0 || $caseSensitivity > 1) {
            throw new InvalidArgumentException('Invalid sensitivity.');
        }

        $this->caseSensitivity = $caseSensitivity;

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
            'query'           => $this->query,
            'operator'        => $this->operator,
            'caseSensitivity' => $this->caseSensitivity,
        ];
    }
}
