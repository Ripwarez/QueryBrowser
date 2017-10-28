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
 * class View
 */
class View
{
    use ViewTrait;

    protected $file;

    protected $data;

    /**
     * Construct a new QueryBrowser\View
     *
     * @param string $file
     * @param array  $data
     *
     * @return void
     */
    public function __construct(string $file, array $data)
    {
        $this->file = $file;
        $this->data = $data;
    }

    /**
     * Render the view
     *
     * Only sets variables that the view may access
     *
     * @return void
     */
    public function render()
    {
        // create the variables
        extract($this->data);

        ob_start();
        include($this->file);
        $html = ob_get_contents();
        ob_end_clean();

        return $html;
    }
}
