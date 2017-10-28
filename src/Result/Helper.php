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

/**
 * class Helper
 */
class Helper
{
    /**
     * Convert boolean to .
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function convertToYesNo($value)
    {
        return ($value) ? 'Yes' : 'No';
    }

    /**
     * .
     *
     * @param string $value
     *
     * @return string
     */
    public static function convertToImage($value)
    {
        return sprintf('<img class="thumbnail img-responsive" src="%s" alt="%s">', $value);
    }
}
