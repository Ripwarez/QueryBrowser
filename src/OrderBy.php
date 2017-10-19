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

namespace QueryBrowser;

use QueryBrowser\Exception\InvalidArgumentException;

/**
 *
 */
class OrderBy
{
	protected $field;

	protected $direction;

	public function __construct(string $field = null, string $direction = null)
	{
		$direction = strtolower($direction);

		if (false === in_array($direction, [null, 'asc', 'desc'])) {
            throw new InvalidArgumentException("The value must be null, 'asc' or 'desc'.");
        }

		$this->field = $field;
		$this->direction = $direction;
	}

	public function getField()
	{
		return $this->field;
	}

	public function setField($field)
	{
		$this->field = $field;

		return $this;
	}

	public function getDirection()
	{
		return $this->direction;
	}

	public function setDirection($direction)
	{
		$this->direction = $direction;

		return $this;
	}
}
