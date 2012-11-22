PHP WebDriver Library
=====================

Requirements
::::::::::::

* **PHP 5.3**
* **Buzz**: HTTP request library

Installation
::::::::::::

This library is distributed via Packagist, as package
``alexandresalome/php-web-driver``. You can use it typing ``php composer.phar
require alexandresalome/php-web-driver``.

Read documentation for more informations about how to use it.

Sample usage
::::::::::::

.. code-block:: php

    use WebDriver\Browser;
    use WebDriver\By;

    $browser = Browser::create('firefox', 'http://localhost:4444/wd/hub');

    $title = $browser->getTitle();
    echo sprintf("Title: %s\n", $title);

    $url = $browser->getTitle();
    echo sprintf("URL: %s\n", $url);

    foreach ($browser->elements(By::tag('a')) as $link) {
        echo sprintf("%s (href: %s)\n", $link->text(), $link->attribute('href')));
    }

Documentation
:::::::::::::

Documentation is located in ``doc/`` for the moment. API should be verbose enough.

References
::::::::::

* WebDriver JSON Wire Protocol: http://code.google.com/p/WebDriver/wiki/JsonWireProtocol
