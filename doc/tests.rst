Testing project
===============

Unit testing
------------

To launch the test suite:

.. code-block:: bash

    ./test.sh

This will execute unit tests of the project. Some tests are ignored by default,
it's the functional tests, explained below.

Functional testing
------------------

This test-suite aims to be used to check behavior against different servers,
platforms and browsers.

This is composed of 2 parts: the test suite and the website demo page.

You need to make your web-server serve the folder ``samples/website``. In
configuration, you will have to define URL of this website.

First, you need to define variables in ``phpunit.xml`` file. Copy from file
``phpunit.xml.dist`` and uncomment the *<php>* tag.

In it, define parameters needed by test suite to run:

* ``WD_SERVER_URL`` is the URL of the Selenium Server or whatever WebDriver
   server.
* ``WD_WEBSITE_URL``: an URL of the website pointing to folder
  ``samples/website``, as mentioned above.
* ``WD_BROWSER``: if you want to test on a specific browser.

Finally, launch PHPUnit again, he will also execute website's tests, using a
runnable WebDriver server.
