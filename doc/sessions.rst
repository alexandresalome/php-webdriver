Sessions
========

The *Session* object is the main entry point for WebDriver API. With it, you
can explore document, navigate and manipulate mouse, keyboard and so on.

Those sessions are getting created by a *Client* object, responsible of
communication with remote WebDriver server (see related chapter).

Here is a full-example for a classic selenium server, running on *localhost*:

.. code-block:: php

    $client  = new WebDriver\Client('http://localhost:4444/wd/hub');
    $session = $client->createSession('firefox');

At this moment, the server should have launched a browser, waiting for your
instructions!

When you're done with the session object, you need to close it explicitly. See
chapter "Finish the session" below to know how and why.

Navigation
----------

.. code-block:: php

    $session->open($url);
    $session->getUrl();
    $session->back();
    $session->forward();

Finish the session
------------------

When you're done with your session, you can call method ``close`` on it:

.. code-block:: php

    $session->close(); // indicates to server the end of communication

This method will make an HTTP request to the server, relying on Buzz. It's not
recommended to add this call to *__destruct* method, since it may happen after
the Buzz client was destructed.

You need to handle it you way in the application. An example integration is
Behat's integration: it's relying on the event ``postSuite``, meaning *after
the test suite running process*.
