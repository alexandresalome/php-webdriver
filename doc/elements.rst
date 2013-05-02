Crawling the page
=================

DOM in browser can be accessed via a fluid interface in library. You need to have
a *Browser* object, and starting from it you can request elements.

Element API
:::::::::::

.. code-block:: php

    $element->getText();
    $element->getAttribute('name');
    $element->click();
    // ...

Requesting a single element
:::::::::::::::::::::::::::

.. code-block:: php

    $title = $browser->element(By::name('title'));
    echo $title->getText();

Upload a file
:::::::::::::

Select your file field, then call method ``upload`` on it with as argument the
path to your file locally:

.. code-block:: php

    $field = $browser->element(By::css('input[type=file]'));

    $field->upload('/path/to/local_file');

This method will always upload file over HTTP, even if it's done locally.

Requesting many elements
::::::::::::::::::::::::

.. code-block:: php

    $links = $browser->elements(By::name('a'));
    foreach ($links as $link) {
        sprintf("%s: %s\n",
            $link->getText(),
            $link->getAttribute('href')
        );
    }
