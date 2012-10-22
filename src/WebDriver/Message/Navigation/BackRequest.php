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
class BackRequest extends Request
{
    public function __construct($sessionId)
    {
        parent::__construct(Request::METHOD_POST, sprintf('/session/%s/back', $sessionId));
    }
}
