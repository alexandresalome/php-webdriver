WebDriver client for PHP
========================

WebDriver is a standard for manipulation of browsers remotely. This protocol
was initiated by Selenium-group.

This library offers a PHP client for such a server, simple as:

.. code-block:: php

    $client  = new Selenium\Client('http://...');
    $browser = $client->createBrowser('firefox');

    $browser->open('http://google.fr');

    $title = $browser->getTitle();

You can easily get a server to run servers on your local machine or remote one:

* http://seleniumhq.org

Documentation
-------------

.. toctree::

    installation
    clients
    browsers
    progress
