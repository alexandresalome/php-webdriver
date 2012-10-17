A WebDriver client
==================

As a WebDriver client, you will first of all request the server for a
**session** meeting a given set of **requirements**.

Constructing a client
---------------------

A *Client* is the instance connected to a server. This client is naturally
constructed with the URL of the remote server:

.. code-block:: php

    use WebDriver\Client;

    $client = new Client('http://selenium-server:4444/wd/hub');

Capabilities
------------

With this library, you will use a *Capabilities* object to express your browser
expectations.

This object is composed of a browser-name and a lot of flags, indicating if
yes or no, a feature is wanted.

This object can be used like this:

.. code-block:: php

    use WebDriver\Capabilities;

    $capabilities = new Capabilities('internet explorer');

    $capabilities->platform = 'WINDOWS';
    $capabilities->version = '7';

Sessions
--------

This client can hold different sessions. Those sessions could be compared to
tabs in a browser. Those different "tabs" of the client are named *Session*
with WebDriver.

To create a new *Session* from a *Client*, use the method *createSession*:

.. code-block:: php

    $client->createSession($capabilities);

See chapter above for more informations about how to create a *Capabilities*
object.

Getting status of a client
--------------------------

.. code-block:: php

    <?php
    $status = $client->getStatus();

    echo sprintf("OS: %s\nServer build time: %s",
        $status->getOs(),
        $status->getBuildTime()
    );
