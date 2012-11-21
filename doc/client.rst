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
``back``, and so on. More on this in next chapter!

Capabilities
------------

If you're not very exhaustive about

We use a *Capabilities* object to express your browser expectations.

You define first the browser you want (firefox, internet explorer) and can tune
lot of flags, like the platform or the version:

.. code-block:: php

    $capabilities = new WebDriver\Capabilities('internet explorer');
    $capabilities->platform = 'WINDOWS';
    $capabilities->version = '6';
    $client->createBrowser($capabilities); // Heehaa

See sourcecode for an exhaustive list of features.

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
