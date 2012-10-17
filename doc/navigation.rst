Navigation
==========

Each *Session* has an associated *Navigation* object:

.. code-block:: php

    $nav = $session->getNavigation();

Go to URL
---------

.. code-block:: php

    $nav->open($url);
