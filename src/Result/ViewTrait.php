<?php

/*
 * This file is part of the QueryBrowser package.
 *
 * (c) Paul Hekkema <paul@hekkema.nl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace QueryBrowser\Result;

/**
 *
 */
trait ViewTrait
{
    /**
     * Replace placeholders within the uri using data from the current result.
     *
     * @param  string $uri
     * @param  array  $result
     *
     * @return string
     */
    public function rewriteUriPlaceholders(string $uri, array $result)
    {
        // find placeholders
        preg_match_all('#<([a-zA-Z0-9_]++)>#', $uri, $matches);

        if (isset($matches[1])) {
            foreach ($matches[1] as $i => $columnId) {
                // get the column value
                if (isset($result[$columnId])) {
                    $uri = str_replace($matches[0][$i], $result[$columnId], $uri);
                }
            }
        }

        return $uri;
    }

    /**
     * Highlight the searchString.
     *
     * @param  string $haystack
     *
     * @return string
     */
    public function highlightString(string $needle, string $haystack, string $class = 'highlight')
    {
        // skip if the value is HTML
        if ('<' === substr($haystack, 0, 1)) {
            return $haystack;
        }

        // escape backslashes
        $needle = str_replace('/', '\/', $needle);

        // replace all occurences
        return preg_replace('/('.$needle.')/i', '<span class="'.$class.'">\\1</span>', $haystack);
    }
}
