<?php

namespace WebDriver\Behat\WebDriverExtension;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use WebDriver\Behat\WebDriverContext;
use WebDriver\Client;

class ContextInitializer implements InitializerInterface
{
    protected $client;
    protected $baseUrl;
    protected $browserName;
    protected $browser;

    public function __construct(Client $client, $baseUrl, $browserName = 'firefox')
    {
        $this->client      = $client;
        $this->baseUrl     = $baseUrl;
        $this->browserName = $browserName;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof WebDriverContext;
    }

    public function initialize(ContextInterface $context)
    {
        $initializer = $this;
        $context->setBrowserInformations(function () use ($initializer) {
            return $initializer->getBrowser();
        }, $this->baseUrl);
    }

    public function getBrowser()
    {
        if (null === $this->browser) {
            $this->browser = $this->client->createBrowser($this->getCapabilities());
        }

        return $this->browser;
    }

    private function getCapabilities()
    {
        return $this->browserName;
    }
}
