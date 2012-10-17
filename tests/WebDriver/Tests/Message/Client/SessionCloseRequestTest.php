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
use WebDriver\Message\Client\SessionCloseRequest;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionCloseRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $request = new SessionCloseRequest('12345');

        $this->assertEquals('/session/12345', $request->getResource());
        $this->assertEquals('DELETE', $request->getMethod());
    }
}
