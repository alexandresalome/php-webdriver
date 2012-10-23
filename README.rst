PHP WebDriver Library
=====================

This is still a work in progress.

Requirements
::::::::::::

* **PHP 5.3**
* **Buzz**: This library is using Buzz for communicating with WebDriver Server.

Installation
::::::::::::

This library is distributed via Packagist, as package
``alexandresalome/php-web-driver``. You can use it typing ``php composer.phar
require alexandresalome/php-web-driver``.

Read documentation for more informations about how to use it.

Sample upsage
:::::::::::::

.. code-block:: php

    $client  = new WebDriver\Client('http://localhost:4444/wd/hub');
    $firefox = $client->createBrowser('firefox');
    $ie      = $client->createBrowser('internet explorer');

    // start fight!

Documentation
:::::::::::::

``doc/`` folder is present.

References
::::::::::

* WebDriver JSON Wire Protocol: http://code.google.com/p/WebDriver/wiki/JsonWireProtocol
