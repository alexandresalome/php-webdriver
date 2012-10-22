<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Message\Session;

use Buzz\Message\Response;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class ScreenshotResponse extends Response
{
    /**
     * @return string Binary PNG data
     */
    public function getScreenshotData()
    {
        $content = str_replace("\0", "", $this->getContent());
        $content = json_decode($content, true);

        return base64_decode($content['value']);
    }
}
