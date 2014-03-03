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
     * @BeforeScenario
     *
     * @When /^I delete cookies$/
     */
    public function iDeleteCookies()
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
     * @When /^I go back$/
     */
    public function iGoBack()
    {
        $this->getBrowser()->back();
    }

    /**
     * @When /^I go forward$/
     */
    public function iGoForward()
    {
        $this->getBrowser()->forward();
    }

    /**
     * @Given /^I am on "((?:[^"]|"")+)"$/
     */
    public function iAmOn($url)
    {
        $url = $this->unescape($url);

        $this->getBrowser()->open($this->getUrl($url));
    }

    /**
     * @Then /^I should be on "((?:[^"]|"")+)"$/
     */
    public function iShouldBeOn($url)
    {
        $url = $this->unescape($url);

        $this->tryRepeating(function ($browser) use ($url) {
            $currentUrl = $browser->getUrl();
            $checkedUrl = $this->getUrl($url);

            if ($currentUrl !== $checkedUrl) {
                throw new \RuntimeException(sprintf('Expected to be on "%s", but found to be on "%s"', $checkedUrl, $currentUrl));
            }
        });
    }

    /**
     * @Then /^title should be "((?:[^"]|"")+)"$/
     */
    public function titleShouldBe($text)
    {
        $text = $this->unescape($text);

        $this->tryRepeating(function ($browser) use ($text) {
            $title = $browser->getTitle();
            if ($text !== $title) {
                throw new \RuntimeException(sprintf('Expected title to be "%s", got "%s"', $text, $title));
            }
        });

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
        $this->browser->execute('window.scrollTo(0, Math.max(document.documentElement.scrollHeight, document.body.scrollHeight, document.documentElement.clientHeight));');
    }

    /**
     * @When /^I scroll to top$/
     */
    public function iScrollToTop()
    {
        $this->browser->execute('window.scrollTo(0,0);');
    }

    /**
     * @Given /^I click on "((?:[^"]|"")+)"$/
     */
    public function iClickOn($text)
    {
        $text = $this->unescape($text);
        $selector = $this->parseSelector($text);

        if (!$selector instanceof By) {
            $selector = By::xpath(strtr(self::CLICKABLE_TEXT_XPATH, array('{text}' => Xpath::quote($text))));
        }

        $this->tryRepeating(function () use ($selector) {
            $this->getElement($selector)->click();
        });
    }

    /**
     * @Then /^I should see (\d+ )?"((?:[^"]|"")*)"$/
     */
    public function iShouldSee($count, $text)
    {
        $count = '' === $count ? '' : (int) $count;
        $text = $this->unescape($text);

        $selector = $this->parseSelector($text);

        $this->tryRepeating(function ($browser) use ($count, $selector) {

            if ($selector instanceof By) {
                $elements = $this->getElements($selector);

                if ($count === '') { // no text means "at least one"
                    if (count($elements) == 0) {
                        throw new \InvalidArgumentException(sprintf("Expected at least one elements, got %s (by: %s).", count($elements), $selector->toString()));
                    }
                } elseif ($count != count($elements)) {
                    throw new \InvalidArgumentException(sprintf("Expected %s elements, got %s (by: %s).", $count, count($elements), $selector->toString()));
                }

            } else {
                $all = $browser->getText();
                $actual = substr_count($all, $selector);

                if ($count === '') { // no text means "at least one"
                    if ($actual == 0) {
                        throw new \RuntimeException('Unable to find "'.$selector.'" in visible text:'."\n".$all);
                    }
                } elseif ($count != $actual) {
                    throw new \RuntimeException('Unable to find "'.$selector.'" in visible text '.$count.' time (found '.$actual.' time) :'."\n".$all);
                }
            }
        });
    }

    /**
     * @Then /^I should not see "((?:[^"]|"")*)"$/
     */
    public function iShouldNotSee($text)
    {
        $this->iShouldSee(0, $text);
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

        $field = $this->tryRepeating(function () use ($selector) {
            return $this->getElement($selector);
        });

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
}
