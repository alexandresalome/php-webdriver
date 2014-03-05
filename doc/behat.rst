Behat extension
===============

This library provides tools for Behat, to help you testing in a web browser.

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

You can configure this extension in your **behat.yml** file:

.. code-block:: yaml

    default:
        extensions:
            WebDriver\Behat\WebDriverExtension\Extension:
                url:      http://localhost:4444/wd/hub
                base_url: http://localhost/
                browser:  firefox
                timeout:  5000

**Configuration options**

* **url**: endpoint URL of selenium server
* **base_url**: URL to your application
* **browser**: browser name to use
* **timeout**: time duration for retrying after failures


Step escaping
-------------

Double quotes if you need to escape values:

.. code-block:: text

    Then I should see "This text has ""some"" quotes"

Available steps
---------------

**Browser features**

    When I refresh

    When I go back

    When I go toward

    When I am on "**/page**"

    Then I should be on "**/page**"

    When I delete cookie "**name**"

    When I delete cookies

    Then title should be "**some text**"

    When I scroll to bottom

    When I scroll to top

**Page assertions**

    Then I should see "**some text**"

    Then I should see **3** "**some text**"

    Then I should see **14** "**xpath=//a**"

    Then I should see **3** "**css=a#link**"

    Then I not should see "**some text**"

    Then I not should see "**xpath=//a**"

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

**Form assertions**

    Then I should see "some text" in field "Some field"

    Then I should see in fields:

.. code-block:: text

    | My field label         | Value of the field |
    | css=input[type=radio]  | 1                  | # 1 for checked, 0 for unchecked
    | xpath=//input          | foobar             |
    | A select field         | The option label   |

**Mouse**

    When I move mouse to "**css=#field**"

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
