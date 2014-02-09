<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver\Tests  ;

use WebDriver\Browser;

/**
 * Base class for functional testing of the website.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WebDriver\Browser
     */
    static protected $browser;

    /**
     * Returns the unique browser.
     *
     * @return WebDriver\Browser
     */
    public function getBrowser()
    {
        if (null === self::$browser) {
            self::$browser = Browser::create($this->getBrowserName(), $this->getServerUrl());
            self::$browser->closeOnDestruct();
        }

        return self::$browser;
    }

    public function getServerUrl()
    {
        return isset($_SERVER['WD_SERVER_URL']) ? $_SERVER['WD_SERVER_URL'] : 'http://localhost:4444/wd/hub';
    }

    /**
     * @return string
     */
    public function getBrowserName()
    {
        return isset($_SERVER['WD_BROWSER']) ? $_SERVER['WD_BROWSER'] : 'firefox';
    }

    /**
     * Returns an URL for the website.
     *
     * @param string $file The file to get
     *
     * @return string The URL
     */
    public function getUrl($file = '/')
    {
        $url = isset($_SERVER['WD_WEBSITE_URL']) ? $_SERVER['WD_WEBSITE_URL'] : 'http://localhost';

        return rtrim($url, '/').'/'.ltrim($file, '/');
    }
}
