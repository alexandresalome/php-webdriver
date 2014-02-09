The Browser object
==================

The *Browser* object is the main entry point for WebDriver API. With it, you
can explore document, navigate, manipulate mouse, keyboard and many more.

Those browsers are created by a *Client* object, responsible of communication
with server. Here is a full-example for a classic selenium server, running on
*localhost*:

.. code-block:: php

    use WebDriver\Client;

    $client  = new Client('http://localhost:4444/wd/hub');
    $browser = $client->createBrowser('firefox');

If you are in a hurry, you can use this useful shortcut:

.. code-block:: php

    use WebDriver\Browser;

    $browser = Browser::create('firefox', 'http://localhost:4444/wd/hub');

When you're done with the browser object, you need to close it explicitly. See
chapter "Finish the browser" below to know how and why.

Navigation
----------

See the example to see how to manipulate history and go to URLs:

.. code-block:: php

    $browser->open('http://www.google.fr');
    $browser->open('http://www.google.pt');

    $url = $browser->getUrl();

    $browser->back();
    $browser->forward();
    $browser->refresh();

Set timeouts
------------

Different type of timeouts exist with Selenium:

* **script timeout**
* **async script timeout**
* **page load**
* **implicit** (when querying elements)

.. code-block:: php

    $browser->setScriptTimeout(5000); // milliseconds
    $browser->setAsyncScriptTimeout(5000); // milliseconds
    $browser->setPageLoadTimeout(5000); // milliseconds
    $browser->setImplicitTimeout(5n000); // milliseconds

Executing Javascript
--------------------

To run a snippet of Javascript, use the ``execute`` method or the
``executeAsync`` method:

.. code-block:: php

    $res = $browser->execute('return ["foo", "bar", 3, true];');
    var_dump($res); // outputs array("foo", "bar", 3, true);

If your script won't return value synchronously, you can use ``executeAsync`` method.
This will let the script run on side until the last argument is invoked:

    $result = $browser->executeAsync('
        var callback = arguments[arguments.length - 1];
        setTimeout(function() {
            callback("result");
        }, 5000);
    ');

If your script takes too much time, a **ScriptTimeoutException** will be thrown.

* `Reference of execute method <https://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/execute>`_
* `Reference of execute_async method <https://code.google.com/p/selenium/wiki/JsonWireProtocol#/session/:sessionId/execute_async>`_

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
