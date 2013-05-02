WebDriver client for PHP
========================

This library allows you to manipulate browsers remotely.

"WebDriver Wire Protocol" was initiated by Selenium-group and consists of a
Restful API to manipulate a browser remotely (cookies, forms, DOM inspection,
screenshots and so on).

PHP client library
------------------

This library implements the WebDriver Wire Protocol in a fluid interface,
feeling like this:

.. code-block:: php

    $browser = WebDriver\Browser::create('firefox', 'http://localhost:4444/wd/hub');
    $browser->open('http://google.fr');

    $title = $browser->getTitle();
    $links = $browser->elements(Selenium\By::tag('a'));

    foreach ($links as $link) {
        echo sprintf("href: %s\n", $link->getAttribute('href'));
        echo sprintf("text: %s\n", $link->getText());
    }

Get the server
--------------

`Download Selenium Server <http://seleniumhq.org>`_ or use Sauce Labs services
to get a "WebDriver server" up and running.

Documentation
-------------

.. toctree::

    installation
    client
    browser
    elements
    cookies
    tests
