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
    $browser->setImplicitTimeout(5000); // milliseconds

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

Alert messages
--------------

You can handle the ``acceptAlert()`` method to simulate the click on "OK" button of the currently displayed alert:

.. code-block:: php

    $browser->acceptAlert();

This method also work for ``confirm()`` dialogs. If you want to click "Cancel" in the confirmation dialog, use method ``dismissAlert()``:

.. code-block:: php

    $browser->dismissAlert();

Handling windows
----------------

If you have multiple windows to handle, this section is for you. Your WebDriver session
is composed of one or many windows.

You can get the list of windows by calling ``getAll()`` method on **WindowList**. You can know
the current window's name by calling ``getCurrent()``:

.. code-block:: php

    $windows = $browser->getWindows()->getAll();
    $current = $browser->getWindows()->getCurrent();

If you want to switch to a given window, use method ``focus($name)``:

.. code-block:: php

    $browser->getWindows()->focus($windows[1]);

You can close current window using ``closeCurrent()``:

.. code-block:: php

    $browser->getWindows()->closeCurrent();

Window size
-----------

If you want to know the size of a window, use method ``getSize()``:

.. code-block:: php

    list($width, $height) = $browser->getWindows()->getSize();

And if you want to change it:

.. code-block:: php

    $browser->getWindows()->setSize(400, 300);

And if you want to maximize:

.. code-block:: php

    $browser->getWindows()->maximize();

Window position
---------------

If you want to know the position of a window, use method ``getPosition()``:

.. code-block:: php

    list($x, $y) = $browser->getWindows()->getPosition();

And if you want to change it:

.. code-block:: php

    $browser->getWindows()->setPosition(400, 300);

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
