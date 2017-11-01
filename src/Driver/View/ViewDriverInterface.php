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
 * Interface implemented by QueryBrowser\Driver\ViewDriver classes.
 *
 * The view driver handles persisting the last known state of a QueryBrowser instance.
 */
interface ViewDriverInterface
{
    /**
     *
     */
    public function render(string $file, array $data): string;
}
