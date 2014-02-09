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
use WebDriver\Util\Zip;

/**
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Element
{
    /**
     * @var Browser
     */
    protected $browser;

    /**
     * server identifier of element.
     *
     * @var string
     */
    protected $id;

    /**
     * Instanciates a new element.
     *
     * @param Browser $browser Browser attached to the element
     * @param string  $id      Identifier of element from server
     */
    public function __construct(Browser $browser, $id)
    {
        $this->browser = $browser;
        $this->id      = $id;
    }

    /**
     * Search an element starting from current element.
     *
     * @param By $by Selection method
     *
     * @return Element
     *
     * @see Browser::element
     */
    public function element(By $by)
    {
        return $this->browser->element($by, $this);
    }

    /**
     * Search for elements starting from current element.
     *
     * @param By $by Selection method
     *
     * @see Browser::elements
     */
    public function elements(By $by)
    {
        return $this->browser->elements($by, $this);
    }

    /**
     * Returns the tag name, in lowercase.
     *
     * @return string
     */
    public function getTagName()
    {
        return strtolower($this->requestValue('name'));
    }

    /**
     * Indicates if an OPTION or INPUT of type radio/checkbox element is selected currently selected.
     *
     * @return boolean
     */
    public function isSelected()
    {
        return $this->requestValue('selected');
    }

    /**
     * Indicates if element is currently displayed on user screen.
     *
     * @return boolean
     */
    public function isDisplayed()
    {
        return $this->requestValue('displayed');
    }

    /**
     * Indicates if element is enabled, or not.
     *
     * Buttons and inputs can be disabled.
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->requestValue('enabled');
    }

    /**
     * Clears the field value.
     */
    public function clear()
    {
        return $this->request('POST', 'clear');
    }

    /**
     * Moves mouse to the element.
     *
     * @param int $x X offset
     * @param int $y Y offset
     *
     * @return Element
     */
    public function moveTo($x = 0, $y = 0)
    {
        $this->browser->moveTo($x, $y, $this);

        return $this;
    }

    /**
     * Returns text representation of element.
     *
     * @return string
     */
    public function getText()
    {
        return $this->requestValue('text');
    }

    public function submit()
    {
        return $this->request('POST', 'submit');
    }

    public function click()
    {
        return $this->request('POST', 'click');
    }

    public function getAttribute($name)
    {
        return $this->requestValue('attribute/'.$name);
    }

    public function type($value)
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

    /**
     * Uploads a file to the selected field.
     *
     * @param string $file Absolute path to the file
     *
     * @return string Path on server.
     */
    public function upload($file)
    {
        $zip = new Zip();
        $zip->addFile($file);

        $response = $this->browser->request('POST', 'file', json_encode(array('file' => base64_encode($zip->getContent()))));
        $content  = json_decode($response->getContent(), true);

        if (!isset($content['value'])) {
            throw new LibraryException('Malformed expression, no key "value" in response: '.$response->getContent());
        }

        $file = $content['value'];

        $this->type($file);

        return $file;
    }

    protected function requestValue($name)
    {
        $response = $this->request('GET', $name);
        $content  = json_decode($response->getContent(), true);

        if (!isset($content['value'])) {
            throw new LibraryException('Malformed expression, no key "value" in response: '.$response->getContent());
        }

        return $content['value'];
    }
}
