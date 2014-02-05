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
                browser:  chrome
                timeout:  5000

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

    Then I should see **3** "**some text**"

    Then I should see **14** "**xpath=//a**"

    Then I should see **3** "**css=a#link**"

    Then I not should see "**some text**"

    Then I not should see "**xpath=//a**"

**Page manipulation**

    When I scroll to bottom

    When I scroll to top

**Mouse**

    When I move mouse to "**css=#field**"

**Elements manipulation**

    When I click on "**some text**"

    When I click on "**xpath=//td//a**"

    When I click on "**css=td a**"

**Form filling**

    When I fill "**My field label**" with "**some value**"

    When I fill:

.. code-block:: text

    | My field label         | Value of the field |
    | css=input[type=radio]  | 1                  | # 1 for checked, 0 for unchecked
    | xpath=//input          | foobar             |
    | A select field         | The option label   |

Create new browser steps
------------------------

If you need to manipulate Browser object, create a context class extending ``AbstractWebDriverContext`` class and implement your business:

.. code-block:: php

    use WebDriver\Behat\AbstractWebDriverContext;

    class MyContext extends AbstractWebDriverContext
    {
        /** @Then /^I should see a unicorn */
        public function iShouldSeeAUnicorn()
        {
            $browser = $this->getBrowser();

            // now, you have a WebDriver\Browser instance
        }
    }

Take a look at the `abstract class <https://github.com/alexandresalome/php-webdriver/blob/master/src/WebDriver/Behat/AbstractWebDriverContext.php>`_ to see facilities provided to you.
