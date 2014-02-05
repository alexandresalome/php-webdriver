<?php

use Behat\Behat\Context\BehatContext;
use WebDriver\Behat\WebDriverContext;

class FeatureContext extends BehatContext
{
    private $rememberedText;

    public function __construct()
    {
        $this->useContext('wd', new WebDriverContext());
    }

    /**
     * @When /^I remember text$/
     */
    public function iRememberText()
    {
        $this->rememberedText = $this->getBrowser()->getText();
    }

    /**
     * @When /^I should see a text different from remembered$/
     */
    public function iShouldSeeaTextDifferentFromRemembered()
    {
        if ($this->rememberedText == $this->getBrowser()->getText()) {
            throw new \RuntimeException('Text has not changed, still: '.$this->rememberedText);
        }
    }

    private function getBrowser()
    {
        return $this->getSubContext('wd')->getBrowser();
    }
}
