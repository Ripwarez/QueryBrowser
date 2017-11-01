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

namespace Hekkema\QueryBrowser\Driver\View;

/**
 * class View
 */
class DefaultViewDriver implements ViewDriverInterface
{
    /**
     * Render the view
     *
     * Only sets variables that the view may access
     *
     * @return void
     */
    public function render(string $file, array $data): string
    {
        // create the variables
        extract($data);

        ob_start();
        include($file);
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
