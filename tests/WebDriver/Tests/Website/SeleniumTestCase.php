<?php
/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests\Website;

/**
 * Base class for functional testing of the website.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class WebDriverTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WebDriver\Session
     */
    static protected $session;

    /**
     * Returns the unique session.
     *
     * @return WebDriver\Session
     */
    public function getSession()
    {
        if (null === self::$session) {
            $client = new \WebDriver\Client('http://localhost:4444/wd/hub');
            self::$session = $client->createSession(new \WebDriver\Capabilities('firefox'));
        }

        return self::$session;
    }

    /**
     * Returns an URL for the website.
     *
     * @param string $file The file to get
     *
     * @return string The URL
     */
    public function getUrl($file)
    {
        return 'http://selenium.local/'.$file;
    }
}
