WebDriver client for PHP
========================

WebDriver is a standard for manipulation of browsers remotely. This protocol
was initiated by Selenium-group.

This library offers a PHP client for such a server, simple as:

.. code-block:: php

    $client  = new Selenium\Client('http://...');
    $session = $client->createSession(new Selenium\Capabilities('firefox'));

You can easily get a WebDriver server:

* http://selenium.org

Documentation
-------------

.. toctree::

    installation
    clients
    sessions
    progress
