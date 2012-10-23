Installation of PHP-WebDriver
=============================

Dependencies
------------

This library depends of Buzz, responsible of communications with remote server.

Requirements
------------

* PHP 5.3
* A server (usually Selenium Server)

Install it with composer
------------------------

In your ``composer.json``, add ``alexandresalome/php-webdriver`` to your *require* section:

.. code-block:: json

    {
        'require': {
            'alexandresalome/php-webdriver'
        }
    }

Then run ``php composer.phar update`` from CLI.

Package on packagist.org: https://packagist.org/packages/alexandresalome/php-webdriver
