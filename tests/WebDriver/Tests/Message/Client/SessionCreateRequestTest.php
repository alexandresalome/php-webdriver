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
use WebDriver\Message\Client\SessionCreateRequest;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionCreateRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $capabilities = new Capabilities('firefox');
        $request = new SessionCreateRequest($capabilities);

        $this->assertEquals('/session', $request->getResource());
        $this->assertEquals('POST', $request->getMethod());

        $content = json_decode($request->getContent(), true);

        $this->assertArrayHasKey('desiredCapabilities', $content);
        $this->assertEquals($capabilities->toArray(), $content['desiredCapabilities']);
    }
}
