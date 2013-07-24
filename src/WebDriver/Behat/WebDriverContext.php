<?php

namespace WebDriver\Behat;

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use WebDriver\By;

class WebDriverContext extends AbstractWebDriverContext
{
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
     * @Then /^I should see (\d+) (xpath|tag|css|class|id|name) elements? "((?:[^"]|"")+)"$/
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

        $elements = $this->getBrowser()->elements(new By($type, $value));

        if (count($elements) != $count) {
            throw new \InvalidArgumentException(sprintf("Expected %s elements, got %s", $count, count($elements)));
        }
    }

    /**
     * @When /^I click on (xpath|css|id|text) "(.*)"$/
     */
    public function iClickOnType($type, $text)
    {
        if ($type == '' || $type == 'text') {
            $selector = By::xpath('//a[contains(text(),"'.$text.'")]|//input[@type="submit" and contains(@value, "'.$text.'")]|//button[contains(text(),"'.$text.'")]|//button[contains(@value, "'.$text.'")]|//button[contains(., "'.$text.'")]');
        } elseif ($type == 'css') {
            $selector = By::css($text);
        } elseif ($type == 'id') {
            $selector = By::id($text);
        } elseif ($type == 'xpath') {
            $selector = By::xpath($text);
        }

        $this->getBrowser()->element($selector)->click();
    }

    /**
     * @Given /^I click on "([^"]*)"$/
     */
    public function iClickOn($text)
    {
        return $this->iClickOnType('text', $text);
    }

    /**
     * @Then /^I should (not )?see "(.*)"$/
     */
    public function iShouldSee($not, $text)
    {
        $all = $this->getBrowser()->element(By::tag('html'))->getText();
        $pos = strpos($all, $text);
        if ($not === "" && false === $pos) {
            throw new \RuntimeException('Unable to find "'.$text.'" in visible text :'."\n".$all);
        } elseif ($not === "not " && false !== $pos) {
            throw new \RuntimeException('Found text "'.$text.'" in visible text :'."\n".$all);
        }
    }

    /**
     * @When /^I fill:$/
     */
    public function iFill(TableNode $table)
    {
        foreach ($table->getRowsHash() as $key => $value) {
            $this->iFillWith($key, $value);
        }
    }

    /**
     * @Then /^I fill "(.*)" with "(.*)"$/
     */
    public function iFillWith($field, $value)
    {
        if (preg_match('/^#/', $field)) {
            $input = $this->getBrowser()->element(By::id(substr($field, 1)));
        } else {
            $label = $this->getBrowser()->element(By::xpath('//label[contains(text(), "'.$field.'")]'));
            $for = $label->getAttribute('for');
            $input = $this->getBrowser()->element(By::id($for));
        }

        if ($input->getTagName() == 'select') {
            $input->element(By::xpath('//option[contains(text(), "'.$value.'")]'))->click();
        } else {
            $input->clear();
            $input->type($value);
        }
    }

    /**
     * @Then /^I fill "([^"]*)" with:$/
     */
    public function iFillWithText($field, PyStringNode $value)
    {
        $this->iFillWith($field, $value->getRaw());
    }

    private function unescape($value)
    {
        return str_replace('""', '"', $value);
    }
}
