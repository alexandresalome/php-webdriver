Using mouse
===========

Move mouse to a location
::::::::::::::::::::::::

.. code-block:: php

    $browser->moveTo(50, 300);
    // or
    $browser->getElement(By::id('button'))->moveTo();
    // or
    $browser->getElement(By::id('button'))->moveTo(5, 5);
