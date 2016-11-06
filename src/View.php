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

use QueryBrowser\ViewTrait;

/**
 * class View
 */
class View
{
    use ViewTrait;

    protected $file;

    protected $data;

    public function __construct($file, &$data)
    {
        $this->file = $file;
        $this->data = $data;
    }

    /**
     * sets only variables that the view may have
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
