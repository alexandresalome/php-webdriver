PHP WebDriver
=============

`Documentation <doc/index.rst>`_

A library to manipulate a WebDriver server.

Installation
::::::::::::

Add the library to your **composer.json**:

.. code-block:: yaml

    {
        "require": {
            "alexandresalome/php-webdriver": "~0.3"
        }
    }

Changelog
:::::::::

**v0.3**

* New method to test if an element is displayed (``$element->isDisplayed()``)
* *Behat*

  * Add a timeout spin on step ``I should not see "some text"``

**v0.2**

* new element method on element ``$element->getElement($by)``

* *Behat*

  * Provide a context for Behat testing

**v0.1**

* Cookie management
* Element crawling
* Javascript methods
* Client & Browser management

References
::::::::::

* WebDriver JSON Wire Protocol: http://code.google.com/p/WebDriver/wiki/JsonWireProtocol
* Selenium downloads: http://docs.seleniumhq.org/download/
