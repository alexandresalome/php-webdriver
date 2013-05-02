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
class Cookie
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @var string|null
     */
    protected $domain;

    /**
     * @var boolean|null
     */
    protected $isSecure;

    /**
     * @var DateTime|null
     */
    protected $expiry;

    /**
     * Cookie constructor.
     *
     * @param string        $name     name of the cookie
     * @param string        $value    value of the cookie
     * @param string|null   $path     path to restrict cookie on (/, /admin, ...)
     * @param string|null   $domain   domain
     * @param boolean|null  $isSecure only through https
     * @param DateTime|null $expiry   limitation in time of cookie
     */
    public function __construct($name, $value, $path = null, $domain = null, $isSecure = null, \DateTime $expiry = null)
    {
        $this->name     = $name;
        $this->value    = $value;
        $this->path     = $path;
        $this->domain   = $domain;
        $this->isSecure = $isSecure;
        $this->expiry   = $expiry;
    }

    /**
     * Creates a Cookie from an array.
     *
     * @param array $array the array to load from
     *
     * @return Cookie a cookie object
     */
    static public function fromArray(array $array)
    {
        $name      = $array['name'];
        $value     = $array['value'];
        $path      = isset($array['path']) ? $array['path'] : null;
        $domain    = isset($array['domain']) ? $array['domain'] : null;
        $isSecure  = isset($array['secure']) ? $array['secure'] : null;
        $expiry    = isset($array['expiry']) ? \DateTime::createFromFormat('U', $array['expiry']) : null;

        return new self($name, $value, $path, $domain, $isSecure, $expiry);
    }

    /**
     * Converts the cookie to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'name'   => $this->name,
            'value'  => $this->value,
            'path'   => $this->path,
            'domain' => $this->domain,
            'secure' => $this->isSecure,
            'expiry' => null === $this->expiry ? null : $this->expiry->getTimestamp()
        );
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    public function isSecure()
    {
        return $this->isSecure;
    }

    public function setSecure($secure)
    {
        $this->secure = $secure;

        return $this;
    }

    public function getExpiry()
    {
        return $this->expiry;
    }

    public function setExpiry(\DateTime $expiry = null)
    {
        $this->name = $name;

        return $this;
    }
}
