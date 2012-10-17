<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Message\Client;

use Buzz\Message\Response;

use WebDriver\ClientStatus;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class StatusResponse extends Response
{
    public function getStatus()
    {
        $content = str_replace("\0", "", $this->getContent());
        return ClientStatus::fromArray(json_decode($content, true));
    }
}
