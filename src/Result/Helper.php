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
