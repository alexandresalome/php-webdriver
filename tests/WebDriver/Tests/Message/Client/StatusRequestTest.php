<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Message\Client;

use WebDriver\Capabilities;
use WebDriver\Message\Client\StatusRequest;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class StatusRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $request = new StatusRequest();

        $this->assertEquals('/status', $request->getResource());
        $this->assertEquals('GET', $request->getMethod());
    }
}
