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
        if (!isset($_SERVER['WD_SERVER_URL']) || !isset($_SERVER['WD_BROWSER'])) {
            $this->markTestSkipped('server URL or browser is not defined in environment variable');

            return;
        }
        if (null === self::$browser) {
            $client = new \WebDriver\Client($_SERVER['WD_SERVER_URL']);
            self::$browser = $client->createBrowser(new \WebDriver\Capabilities($_SERVER['WD_BROWSER']));
        }

        return self::$browser;
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
        if (!isset($_SERVER['WD_WEBSITE_URL'])) {
            $this->markTestSkipped('website URL is not defined in environment variable');

            return;
        }

        $url = $_SERVER['WD_WEBSITE_URL'];

        if (!preg_match('#/$#', $url)) {
            $url .= '/';
        }

        return $url.$file;
    }
}
