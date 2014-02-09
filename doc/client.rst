The Client object
=================

A *Client* is the instance connected to a server. This client is naturally
constructed with the URL of the remote server:

.. code-block:: php

    use WebDriver\Client;

    $client = new Client('http://selenium-server:4444/wd/hub');

From this *Client* object, you will create different browsers:

.. code-block:: php

    $browser = $client->createBrowser(new WebDriver\Capabilities('firefox'));
    $browser = $client->createBrowser('firefox'); // Short version

The *Browser* provides facilities to manipulate the browser, like ``open``,
``refresh``... more on this in next chapter!

Capabilities
------------

A *Capabilities* object is used to specify the browser you want. Instanciate it with a browser name, like this:

.. code-block:: php

    use WebDriver\Capabilities;

    $capabilities = new Capabilities('internet explorer');
    $capabilities->platform = 'WINDOWS';
    $capabilities->version = '6';

    $browser = $client->createBrowser($capabilities);

See `Capabilities specification <http://www.w3.org/TR/webdriver/#browser-capabilities>`_ for details about it.

Getting status of the server
----------------------------

Status allow you to know more about the server you are running against.

.. code-block:: php

    <?php
    $status = $client->getStatus();

    echo sprintf("OS: %s\nServer build time: %s",
        $status->getOs(),
        $status->getBuildTime()
    );
