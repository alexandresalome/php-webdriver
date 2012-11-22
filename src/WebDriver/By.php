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
 * Allow to express an element selection on a given page.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class By
{
    const CLASS_NAME        = 'class name';
    const CSS_SELECTOR      = 'css selector';
    const ID                = 'id';
    const NAME              = 'name';
    const LINK_TEXT         = 'link text';
    const PARTIAL_LINK_TEXT = 'partial link text';
    const TAG_NAME          = 'tag name';
    const XPATH             = 'xpath';

    protected $using;
    protected $value;

    /**
     * Instanciates a new element selector.
     *
     * @param string $using A type of selection (css selector, id, name, link text, partial link text, tag name or xpath)
     * @param string $value The value of selection, depending of the type
     */
    public function __construct($using, $value)
    {
        if (!in_array($using, $this->getTypes())) {
            throw new LibraryException(sprintf('Unexpected selection of elements using "%s", known are: %s',
                $using,
                implode(' ', $this->getTypes())
            ));
        }

        $this->using = $using;
        $this->value = $value;
    }

    /**
     * Converts the selector to an array.
     *
     * @return array An array with two indexes: using and value
     */
    public function toArray()
    {
        return array(
            'using' => $this->using,
            'value' => $this->value
        );
    }

    /**
     * Returns an element whose class name contains the search value;
     * compound class names are not permitted.
     *
     * @param string $value A class name
     *
     * @return By
     */
    static public function className($value)
    {
        return new By(self::CLASS_NAME, $value);
    }

    /**
     * Returns an element matching a CSS selector.
     *
     * @param string $value A CSS selector
     *
     * @return By
     */
    static public function css($value)
    {
        return new By(self::CSS_SELECTOR, $value);
    }

    /**
     * Returns an element whose ID attribute matches the search value.
     *
     * @param string $value An ID attribute value
     *
     * @return By
     */
    static public function id($value)
    {
        return new By(self::ID, $value);
    }

    /**
     * Returns an element whose NAME attribute matches the search value.
     *
     * @param string $value A name attribute value
     *
     * @return By
     */
    static public function name($value)
    {
        return new By(self::NAME, $value);
    }

    /**
     * Returns an anchor element whose visible text matches the search value.
     *
     * Browser will search for exact text if you don't specify $isPartial argument to true.
     *
     * @param string  $value     Exact text to search in a link
     * @param boolean $isPartial Set to true to enable partial text search
     *
     * @return By
     */
    static public function linkText($value, $isPartial = false)
    {
        return new By($isPartial ? self::PARTIAL_LINK_TEXT : self::LINK_TEXT, $value);
    }

    /**
     * Returns an element whose tag name matches the search value.
     *
     * @param string $value A tag name
     *
     * @return By
     */
    static public function tag($value)
    {
        return new By(self::TAG_NAME, $value);
    }

    /**
     * Select with an XPATH expression.
     *
     * @param string $value XPath expression
     *
     * @return By
     */
    static public function xpath($value)
    {
        return new By(self::XPATH, $value);
    }

    /**
     * Returns an array with selection types (class name, id, name, etc.).
     *
     * @return array
     */
    protected function getTypes()
    {
        return array(
            self::CLASS_NAME,
            self::CSS_SELECTOR,
            self::ID,
            self::NAME,
            self::LINK_TEXT,
            self::PARTIAL_LINK_TEXT,
            self::TAG_NAME,
            self::XPATH
        );
    }
}
