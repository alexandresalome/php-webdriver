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
use WebDriver\Message\Client\SessionCreateResponse;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class SessionCreateResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $response = new SessionCreateResponse();
        $response->addHeader('1.0 302 Moved Temporarly');
        $response->addHeader('Location: http://localhost/session/12345');

        $this->assertEquals(12345, $response->getSessionId());
    }

    public function testIncorrectCode()
    {
        try {
            $response = new SessionCreateResponse();
            $response->addHeader('1.0 200 OK');

            $response->getSessionId();
        } catch (\RuntimeException $e) {
            $this->assertEquals('The response should be a redirection, response code from server was "200"', $e->getMessage());
        }
    }

    public function testWrongRedirection()
    {
        try {
            $response = new SessionCreateResponse();
            $response->addHeader('1.0 302 Moved Temporarly');
            $response->addHeader('Location: /foo/bar');

            $response->getSessionId();
        } catch (\RuntimeException $e) {
            $this->assertEquals('The Location should end with /session/<session-id> (location returned: /foo/bar)', $e->getMessage());
        }
    }
}
