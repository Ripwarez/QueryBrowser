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
     * @param  string  $v  value
     * @return string
     */
    public static function YesNo($v)
    {
        return ($v) ? 'Yes' : 'No';
    }

    /**
     * .
     *
     * @param  string  $v  value
     * @return string
     */
    public static function Image($v, $row, $path)
    {
        return sprintf('<img class="thumbnail img-responsive" src="%s" alt="%2$s">', URL::image(sprintf('%s/%s', $path, $v), 'w64-h64-c'), $v);
    }
}
