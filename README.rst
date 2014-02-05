PHP WebDriver - Library and Behat extension
===========================================

This library allows you to manipulate browsers remotely, using a raw PHP API or a Behat extension.

**WebDriver** was initiated by Selenium-group and consists of a Restful API to manipulate a browser remotely (cookies, forms, DOM inspection, screenshots...).

This library provides a PHP interface for WebDriver server manipulation.

Documentation:

* The library

  * `The Client object <doc/client.rst>`_
  * `The Browser object <doc/browser.rst>`_
  * `Crawling the page <doc/elements.rst>`_
  * `Using mouse <doc/mouse.rst>`_
  * `Cookies <doc/cookies.rst>`_

* `Behat extension <doc/behat.rst>`_
* `Testing <doc/tests.rst>`_

Installation
::::::::::::

Add the library to your **composer.json**:

.. code-block:: yaml

    {
        "require": {
            "alexandresalome/php-webdriver": "~1.0"
        }
    }

Changelog
:::::::::

**v1.0**

* Cleanup Behat sentences
* Retry "should see" tests in case of failure (for Ajax features)

**v0.5**

* New methods to move mouse: ``$browser->moveTo()`` and ``$element->moveTo()``: moves mouse to absolute or relative position
* ``$browser->getText()``: returns text of the browser
* *Behat*

  * New step: *I move mouse to "css=h1"*

**v0.4**

* ``timeout`` is now configurable in Behat extension

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

Roadmap
:::::::

**v1.0**

* Behat extension tests

**Unplanned**

* Complete WebDriver implementation

References
::::::::::

* WebDriver JSON Wire Protocol: http://www.w3.org/TR/webdriver/
* Selenium downloads: http://docs.seleniumhq.org/download/
