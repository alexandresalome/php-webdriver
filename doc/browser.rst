The Browser object
==================

The *Browser* object is the main entry point for WebDriver API. With it, you
can explore document, navigate, manipulate mouse, keyboard and many more.

Those browser are created by a *Client* object, responsible of communication
with server. Here is a full-example for a classic selenium server, running on
*localhost*:

.. code-block:: php

    $client  = new WebDriver\Client('http://localhost:4444/wd/hub');
    $browser = $client->createBrowser('firefox');

When you're done with the browser object, you need to close it explicitly. See
chapter "Finish the browser" below to know how and why.

Navigation
----------

.. code-block:: php

    $browser->open($url);
    $browser->getUrl();
    $browser->back();
    $browser->forward();

Finish the browser
------------------

When you're done with your browser, you can call method ``close`` on it:

.. code-block:: php

    $browser->close(); // indicates to server the end of communication

This method will make an HTTP request to the server, relying on Buzz. It's not
recommended to add this call to *__destruct* method, since it may happen after
the Buzz client was destructed.

You need to handle it you way in the application. An example integration is
Behat's integration: it's relying on the event ``postSuite``, meaning *after
the test suite running process*.
