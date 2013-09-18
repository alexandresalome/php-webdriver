Crawling the page
=================

DOM in browser can be accessed via a fluid interface in library. You need to have
a *Browser* object, and starting from it you can request elements.

Requesting a single element
:::::::::::::::::::::::::::

.. code-block:: php

    $title = $browser->element(By::name('title'));
    echo $title->getText();

Requesting multiple elements
::::::::::::::::::::::::::::

    $links = $browser->elements(By::name('a'));
    foreach ($links as $link) {
        //...
    }

Element API
:::::::::::

.. code-block:: php

    // returns text version of the element
    $element->getText();

    // returns HTML DOM attribute
    $element->getAttribute('name');

    // click the element
    $element->click();

    // indicates if the element is visible on screen
    $element->isDisplayed();
    // ...

Upload a file
:::::::::::::

Select your file field, then call method ``upload`` on it with as argument the
path to your file locally:

.. code-block:: php

    $field = $browser->element(By::css('input[type=file]'));

    $field->upload('/path/to/local_file');

This method will always upload file over HTTP, even if it's done locally.
