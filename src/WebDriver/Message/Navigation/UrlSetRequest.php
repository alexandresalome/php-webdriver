<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Message\Navigation;

use Buzz\Message\Request;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class UrlSetRequest extends Request
{
    public function __construct($sessionId, $url)
    {
        parent::__construct(Request::METHOD_POST, sprintf('/session/%s/url', $sessionId));

        $this->setContent(json_encode(array('url' => $url)));
    }
}
