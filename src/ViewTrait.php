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

trait ViewTrait
{
    
    /**
     * Replace placeholders within the uri using data from the current result.
     *
     * @param  string  $uri
     * @param  array   $result
     * @return string
     */
    public function rewriteUriPlaceholders($uri, &$result)
    {
        // find placeholders
        preg_match_all('#<([a-zA-Z0-9_]++)>#', $uri, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $i => $columnKey) {
                // get the column value
                if (isset($result[$columnKey])) {
                    $uri = str_replace($matches[0][$i], $result[$columnKey], $uri);
                }
            }
        }

        return $uri;
    }

    /**
     * Highlight the searchString.
     *
     * @param  string  $haystack
     * @return string
     */
    public function highlightString($needle, $haystack)
    {
        if (substr($haystack, 0, 1) != '<') {
            return preg_replace(sprintf('/(%s)/i', str_replace('/', '\/', $needle)), '<span class="highlight">\\1</span>', $haystack);
        }

        return $haystack;
    }
}
