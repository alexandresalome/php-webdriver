<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class CookieBag
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @param Browser $browser Browser attached to the cookie bag
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * Fetches value of a given cookie.
     *
     * @return string|null the cookie or null if cookie is not found.
     */
    public function getValue($name)
    {
        $cookie = $this->get($name);

        return null === $cookie ? null : $cookie->getValue();
    }

    /**
     * Returns a Cookie.
     *
     * @return Cookie|null returns cookie or null if cookie was not found
     */
    public function get($name)
    {
        $cookies = $this->getAll();

        foreach ($cookies as $cookie) {
            if ($cookie->getName() == $name) {
                return $cookie;
            }
        }

        return null;
    }

    /**
     * Deletes all visible cookies.
     */
    public function deleteAll()
    {
        $this->request('DELETE', 'cookie');
    }

    public function getAll()
    {
        return array_map(function ($array) {
            return Cookie::fromArray($array);
        }, $this->requestValue('cookie'));
    }

    /**
     * Returns a cookie value.
     *
     * @return string|null returns the cookie value or null if cookie does not exist
     */
    public function set($name, $value, $path = null, $domain = null, $isSecure = null, \DateTime $expiry = null)
    {
        $cookie = new Cookie($name, $value, $path, $domain, $isSecure, $expiry);

        $this->setCookie($cookie);
    }

    /**
     * Sets a cookie in browser.
     */
    public function setCookie(Cookie $cookie)
    {
        $this->browser->request('POST', 'cookie', json_encode(array('cookie' => $cookie->toArray())));
    }

    public function requestValue($path)
    {
        return $this->browser->requestValue($path);
    }

    public function request($verb, $path = null, $content = null, array $headers = array())
    {
        return $this->browser->request($verb, $path, $content, $headers);
    }
}
