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

To launch website tests, you must define three environment variables:

* ``WD_SERVER_URL`` is the URL of the Selenium Server or whatever WebDriver
   server.
* ``WD_WEBSITE_URL``: an URL of the website pointing to folder
  ``samples/website``, as mentioned above.
* ``WD_BROWSER``: if you want to test on a specific browser.

First, you need to define variables in ``phpunit.xml`` file. Copy from file
``phpunit.xml.dist`` and uncomment the *<php>* tag.

Finally, launch PHPUnit again, he will also execute website's tests, using a
runnable WebDriver server.
