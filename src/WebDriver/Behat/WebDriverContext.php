<?php

namespace WebDriver\Behat;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use WebDriver\By;
use WebDriver\Util\Xpath;

class WebDriverContext extends AbstractWebDriverContext
{
    /**
     * Timeout to wait for text to be visible (in ms).
     */
    const DEFAULT_SHOULD_SEE_TIMEOUT = 5000;

    protected $shouldSeeTimeout = self::DEFAULT_SHOULD_SEE_TIMEOUT;

    /**
     * Set timeout for "shouldSee" methods.
     *
     * @param int $shouldSeeTimeout (in milliseconds)
     *
     * @return WebDriverContext
     */
    public function setShouldSeeTimeout($shouldSeeTimeout)
    {
        $this->shouldSeeTimeout = $shouldSeeTimeout;

        return $this;
    }

    /**
     * @BeforeScenario
     */
    public function deleteCookies()
    {
        $this->getBrowser()->getCookies()->deleteAll();
    }

    /**
     * @When /^I refresh$/
     */
    public function iRefresh()
    {
        $this->getBrowser()->refresh();
    }

    /**
     * @Given /^I am on "(.+)"$/
     */
    public function iAmOn($url)
    {
        $this->getBrowser()->open($this->getUrl($url));
    }

    /**
     * @Then /^I should be on "(.+)"$/
     */
    public function iShouldBeOn($url)
    {
        $currentUrl = $this->getBrowser()->getUrl();
        $checkedUrl = $this->getUrl($url);

        if ($currentUrl !== $checkedUrl) {
            throw new \RuntimeException(sprintf('Expected to be on "%s", but found to be on "%s"', $checkedUrl, $currentUrl));
        }
    }

    /**
     * @Then /^title should be "(.*)"$/
     */
    public function iShouldSeeATitle($text)
    {
        $title = $this->getBrowser()->getTitle();
        if ($text !== $title) {
            throw new \RuntimeException(sprintf('Expected title to be "%s", got "%s"', $text, $title));
        }
    }

    /**
     * @Then /^I should see (\d+) (xpath|tag|css|class|id|name) elements "((?:[^"]|"")+)"$/
     */
    public function iShouldSeeElements($count, $type, $value)
    {
        $value = $this->unescape($value);
        if ($type == 'tag') {
            $type = 'tag name';
        } elseif ($type == 'css') {
            $type = 'css selector';
        } elseif ($type == 'class') {
            $type = 'class name';
        }

        $elements = $this->getElements(new By($type, $value));

        if (count($elements) != $count) {
            throw new \InvalidArgumentException(sprintf("Expected %s elements, got %s", $count, count($elements)));
        }
    }

    /**
     * @When /^I click on (xpath|css|id|text) "((?:[^"]|"")+)"$/
     */
    public function iClickOnType($type, $text)
    {
        $text = $this->unescape($text);

        if ($type == '' || $type == 'text') {
            $selector = By::xpath(strtr(self::CLICKABLE_TEXT_XPATH, array('{text}' => Xpath::quote($text))));
        } elseif ($type == 'css') {
            $selector = By::css($text);
        } elseif ($type == 'id') {
            $selector = By::id($text);
        } elseif ($type == 'xpath') {
            $selector = By::xpath($text);
        }

        $this->getElement($selector)->click();
    }

    /**
     * @When /^I move mouse to "((?:[^"]|"")+)"$/
     */
    public function iMoveMouseTo($text)
    {
        $text = $this->parseSelector($this->unescape($text));

        if (!$text instanceof By) {
            throw new \InvalidArgumentException(sprintf('Expected an expression with a type (css=..., id=...) and got "%s".', $text));
        }

        $this->getElement($text)->moveTo();
    }

    /**
     * @When /^I scroll to bottom$/
     */
    public function iScrollToBottom()
    {
        $javascript = <<<JAVASCRIPT
        window.scrollTo(0,Math.max(
            document.documentElement.scrollHeight,
            document.body.scrollHeight,
            document.documentElement.clientHeight
        ));
JAVASCRIPT;
        $this->browser->execute($javascript);
    }

    /**
     * @When /^I scroll to top$/
     */
    public function iScrollToTop()
    {
        $javascript = <<<JAVASCRIPT
        window.scrollTo(0,0);
JAVASCRIPT;
        $this->browser->execute($javascript);
    }

    /**
     * @Given /^I click on "((?:[^"]|"")+)"$/
     */
    public function iClickOn($text)
    {
        return $this->iClickOnType('text', $text);
    }

    /**
     * @Then /^I should see "((?:[^"]|"")*)"$/
     */
    public function iShouldSee($text)
    {
        $text = $this->unescape($text);
        $time = $this->shouldSeeTimeout;
        $all = '';

        while ($time > 0) {
            $all = $this->getBrowser()->element(By::tag('html'))->getText();
            $pos = strpos($all, $text);

            if (false !== $pos) {
                return;
            }

            $wait = min(1000, $time);
            $time -= $wait;
            usleep($wait*1000);
        }

        throw new \RuntimeException('Unable to find "'.$text.'" in visible text :'."\n".$all);
    }

    /**
     * @Then /^I should not see "((?:[^"]|"")*)"$/
     */
    public function iShouldNotSee($text)
    {
        $text = $this->unescape($text);
        $time = $this->shouldSeeTimeout;
        $all = '';

        while ($time > 0) {
            $all = $this->getBrowser()->element(By::tag('html'))->getText();
            $pos = strpos($all, $text);

            if (false === $pos) {
                return;
            }

            $wait = min(1000, $time);
            $time -= $wait;
            usleep($wait*1000);
        }

        throw new \RuntimeException(sprintf('Found text "%s" in visible text "%s".', $text, $all));
    }

    /**
     * @When /^I fill:$/
     */
    public function iFill(TableNode $table)
    {
        foreach ($table->getRowsHash() as $key => $value) {
            $this->iFillWith($this->escape($key), $this->escape($value));
        }

    }

    /**
     * @Then /^I fill "((?:[^"]|"")*)" with "((?:[^"]|"")*)"$/
     */
    public function iFillWith($field, $value)
    {
        $field = $this->unescape($field);
        $value = $this->unescape($value);

        $selector = $this->parseSelector($field);
        if (is_string($selector)) {
            $selector = By::xpath(strtr(self::LABEL_TO_INPUT_XPATH, array('{text}' => Xpath::quote($field))));
        }

        $field = $this->getElement($selector);

        if ($field->getTagName() == 'select') {
            $field->element(By::xpath('.//option[contains(., '.Xpath::quote($value).')]'))->click();

            return;
        }

        $type = $field->getAttribute('type');

        if ($type === 'checkbox') {
            $value = $value === '1' || $value === 'on' || $value === 'true';
            $selected = $field->isSelected();

            if ($value !== $selected) {
                $field->click();
            }

            return;
        }

        if ($type === 'radio') {
            $value = $value === '1' || $value === 'on' || $value === 'true';
            $selected = $field->isSelected();

            if ($selected && !$value) {
                throw new \RuntimeException(sprintf('Cannot uncheck a radio (a user in a web browser can\'t neither'));
            }

            $field->click();

            return;
        }

        // text or textarea
        $field->clear();
        $field->type($value);
    }

    /**
     * @When /^I delete cookie "([^"]+)"$/
     */
    public function iDeleteCookie($name)
    {
        $this->getBrowser()->getCookies()->delete($name);
    }

    private function unescape($value)
    {
        return str_replace('""', '"', $value);
    }

    private function escape($value)
    {
        return str_replace('"', '""', $value);
    }
}
