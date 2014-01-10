Behat extension
===============

This library provides tools for Behat, to help you testing in a web browser.

When you `added package to your composer file <../README.rst>`_, configure
Behat like this:

.. code-block:: yaml

    default:
        extensions:
            WebDriver\Behat\WebDriverExtension\Extension:
                base_url: http://localhost/
                browser: chrome

In your **FeatureContext** class, add WebDriver's context:

.. code-block:: php

    use WebDriver\Behat\WebDriverContext;

    class FeatureContext extends BehatContext
    {
        public function __construct(array $parameters)
        {
            $this->useContext('webdriver', new WebDriverContext());
        }
    }

Step escaping
-------------

Double quotes if you need to escape values:

.. code-block:: text

    Then I should see "This text has ""some"" quotes"

Available steps
---------------

**Browser features**

    When I refresh

    When I am on "**/page**"

    When I delete cookie "**name**"

**Page assertions**

    Then I should be on "**/page**"

    Then title should be "**some text**"

    Then I should see "**some text**"

    Then I not should see "**some text**"

**Elements assertions**

    Then I should see **14** **xpath** elements "**//a**"

    Then I should see **3** **css** elements "**a#link**"

**Page manipulation**

    When I scroll to bottom
    
    When I scroll to top

**Elements manipulation**

    When I click on "**some text**"

    When I click on **xpath** "**//td//a**"

    When I click on **css** "**td a**"

**Form filling**

    When I fill "**My field label**" with "**some value**"

    When I fill:

.. code-block:: text

    | My field label         | Value of the field |
    | css=input[type=radio]  | 1                  | # 1 for checked, 0 for unchecked
    | xpath=//input          | foobar             |
    | A select field         | The option label   |

