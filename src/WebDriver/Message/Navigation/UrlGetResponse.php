<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Message\Navigation;

use Buzz\Message\Response;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class UrlGetResponse extends Response
{
    public function getUrl()
    {
        $content = str_replace("\0", "", $this->getContent());
        $content = json_decode($content, true);

        return $content['value'];
    }
}
