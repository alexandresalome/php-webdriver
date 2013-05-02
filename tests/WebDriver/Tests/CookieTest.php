<?php

namespace WebDriver\Tests;

use WebDriver\Cookie;

class CookieTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArray_complete()
    {
        $dt = new \DateTime('+2 days');
        $array = array(
            'name'  => 'foo',
            'value' => 'bar',
            'path'  => '/admin',
            'domain' => 'admin.example.org',
            'secure' => false,
            'expiry' => $dt->getTimestamp()
        );

        $cookie = Cookie::fromArray($array);

        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());
        $this->assertEquals('/admin', $cookie->getPath());
        $this->assertEquals('admin.example.org', $cookie->getDomain());
        $this->assertEquals(false, $cookie->isSecure());
        $this->assertEquals($dt->getTimestamp(), $cookie->getExpiry()->getTimestamp());
    }

    public function testFromArray_minimal()
    {
        $array = array(
            'name'  => 'foo',
            'value' => 'bar'
        );

        $cookie = Cookie::fromArray($array);

        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());
    }
}
