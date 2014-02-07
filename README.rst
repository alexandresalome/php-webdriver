PHP WebDriver - WebDriver client and Behat extension
====================================================

* `Tests status <https://travis-ci.org/alexandresalome/php-webdriver>`_ |test_status|

.. |test_status| image:: https://travis-ci.org/alexandresalome/php-webdriver.png
   :alt: Build status
   :target: https://travis-ci.org/alexandresalome/php-webdriver

This library provides 2 things:

* A library with a proper API
* A Behat extension to ease testing through a browser

**WebDriver** was initiated by Selenium-group and consists of a Restful API to manipulate a browser remotely (cookies, forms, DOM inspection, screenshots...).

This library provides a PHP interface for WebDriver server manipulation.

Documentation
-------------

* **The library**

  * `The Client object <doc/client.rst>`_
  * `The Browser object <doc/browser.rst>`_
  * `Crawling the page <doc/elements.rst>`_
  * `Using mouse <doc/mouse.rst>`_
  * `Cookies <doc/cookies.rst>`_

* **Behat extension**

  * `Behat extension <doc/behat.rst>`_

* `Testing <doc/tests.rst>`_

Installation
------------

Add the library to your **composer.json**:

.. code-block:: yaml

    {
        "require": {
            "alexandresalome/php-webdriver": "~0.6"
        }
    }


Roadmap
:::::::

**v0.6**

* Tests and integration trough travis-ci.org

**Unplanned**

* Complete WebDriver implementation

Changelog
---------

**v0.6**

* Cleanup Behat sentences
* Retry "should see" tests in case of failure (for Ajax features)

**BC break**

* Behat: **I should see 3 xpath elements "//a"** has been removed in favor of **I should see 3 "xpath=//a"**
* Behat: **I click on xpath "//a"** has been removed in favor of **I click on "xpath=//a"**

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

References
::::::::::

* WebDriver JSON Wire Protocol: http://www.w3.org/TR/webdriver/
* Selenium downloads: http://docs.seleniumhq.org/download/
