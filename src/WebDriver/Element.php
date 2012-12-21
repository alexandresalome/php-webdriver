<?php

/*
 * This file is part of PHP WebDriver Library.
 * (c) Alexandre Salomé <alexandre.salome@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WebDriver;

use WebDriver\Exception\LibraryException;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Element
{
    protected $browser;
    protected $id;

    public function __construct(Browser $browser, $id)
    {
        $this->browser = $browser;
        $this->id      = $id;
    }

    public function element(By $by)
    {
        return $this->browser->element($by, $this);
    }

    public function elements(By $by)
    {
        return $this->browser->elements($by, $this);
    }

    /**
     * Returns the tag name.
     *
     * @return string
     */
    public function name()
    {
        return $this->getValue('name');
    }

    public function clear()
    {
        return $this->request('POST', 'clear');
    }

    public function text()
    {
        return $this->getValue('text');
    }

    public function submit()
    {
        return $this->request('POST', 'submit');
    }

    public function click()
    {
        return $this->request('POST', 'click');
    }

    public function attribute($name)
    {
        return $this->getValue('attribute/'.$name);
    }

    public function value($value)
    {
        $this->request('POST', 'value', json_encode(array('value' => array($value))));
    }

    public function request($verb, $path, $content = null, array $headers = array())
    {
        return $this->browser->request($verb, sprintf('element/%s/%s', $this->id, $path), $content, $headers);
    }

    public function getId()
    {
        return $this->id;
    }

    protected function getValue($name)
    {
        $response = $this->request('GET', $name);
        $content  = json_decode($response->getContent(), true);

        if (!isset($content['value'])) {
            throw new LibraryException('Malformed expression, no key "value" in response: '.$response->getContent());
        }

        return $content['value'];
    }
}
