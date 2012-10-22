<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace WebDriver\Tests\Message\Navigation;

use WebDriver\Message\Navigation\BackRequest;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class BackRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testSimple()
    {
        $request = new BackRequest('12345');

        $this->assertEquals('/session/12345/back', $request->getResource());
        $this->assertEquals('POST', $request->getMethod());
    }
}
