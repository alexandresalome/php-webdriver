Managing cookies
================

With WebDriver API, you can create, update, and delete cookies in a remote
browser.

.. code-block:: php

    $browser = WebDriver\Browser::create('firefox', 'http://localhost:4444/wd/hub');

Read cookies value
------------------

To read a cookie value, method is:

.. code-block:: php

    $value = $browser->getCookies()->getValue('cookie_name');

    if ($value === null) {
        // cookies was not found
    } else {
        // string value of the cookie
    }

If you want to get more details on the cookie, use ``getCookie`` method:

.. code-block:: php

    $cookie = $browser->getCookies()->get('cookie_name');

    if (null === $cookie) {
        // cookie was not found
    } else {
        // see Cookie API
    }

To read all visible cookies on the current page, use ``getAll`` method on the
cookie bag:

.. code-block:: php

    foreach ($browser->getCookies()->getAll() as $cookie) {
        echo $cookie->getName(), " ", $cookie->getValue(), "\n";
    }

Create a cookie
---------------

If you quickly want to store a new cookie in your browser:

.. code-block:: php

    $browser->getCookies()->set('name', 'value of the cookie');

If you want to set it more specifically:

.. code-block:: php

    $browser->getCookies()->set(
        'name',
        'value of the cookie',
        '/admin',
        'admin.example.org',
        true, new \DateTime('+1 hours')
    );

Delete cookies
--------------

To delete all cookies visible to the browser, run:

.. code-block:: php

    $browser->getCookies()->deleteAll();

If you want to delete a specific cookie:

.. code-block:: php

    $browser->getCookies()->delete('cookie_name');

