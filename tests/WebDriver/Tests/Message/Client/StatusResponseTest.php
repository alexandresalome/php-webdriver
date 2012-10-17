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
use WebDriver\Message\Client\StatusResponse;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class StatusResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $response = new StatusResponse();
        $response->setContent(json_encode(array(
            'sessionId' => 'foo',
            'status'    => 0,
            'value'     => array(
                'os' => array('arch' => 'foo', 'name' => 'bar', 'version' => 'baz'),
                'java' => array('version' => '2.3'),
                'build' => array('revision' => 'r1', 'time' => '2012', 'version' => '4.5')
            )
        )));

        $status = $response->getStatus();

        $this->assertEquals('bar baz (arch: foo)', $status->getOs());

        $this->assertEquals('foo', $status->getSessionId());
        $this->assertEquals(0, $status->getStatus());
        $this->assertEquals('foo', $status->getOsArchitecture());
        $this->assertEquals('bar', $status->getOsName());
        $this->assertEquals('baz', $status->getOsVersion());
        $this->assertEquals('2.3', $status->getJavaVersion());
        $this->assertEquals('r1', $status->getBuildRevision());
        $this->assertEquals('2012', $status->getBuildTime());
        $this->assertEquals('4.5', $status->getBuildVersion());
    }
}
