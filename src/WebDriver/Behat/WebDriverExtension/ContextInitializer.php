<?php

namespace WebDriver\Behat\WebDriverExtension;

use Behat\Behat\Context\ContextInterface;
use Behat\Behat\Context\Initializer\InitializerInterface;
use WebDriver\Behat\AbstractWebDriverContext;
use WebDriver\Client;

class ContextInitializer implements InitializerInterface
{
    protected $client;
    protected $baseUrl;
    protected $browserName;
    protected $browser;
    protected $timeout;

    public function __construct(Client $client, $baseUrl, $browserName = 'firefox', $timeout = 5000)
    {
        $this->client      = $client;
        $this->baseUrl     = $baseUrl;
        $this->browserName = $browserName;
        $this->timeout     = $timeout;
    }

    public function supports(ContextInterface $context)
    {
        return $context instanceof AbstractWebDriverContext;
    }

    public function initialize(ContextInterface $context)
    {
        $initializer = $this;
        $context->setBrowserInformations(function () use ($initializer) {
            return $initializer->getBrowser();
        }, $this->baseUrl, $this->timeout);
    }

    public function getBrowser()
    {
        if (null === $this->browser) {
            $this->browser = $this->client->createBrowser($this->getCapabilities());
            $this->browser->setImplicitTimeout(0); // managed by context class
        }

        return $this->browser;
    }

    private function getCapabilities()
    {
        return $this->browserName;
    }
}
