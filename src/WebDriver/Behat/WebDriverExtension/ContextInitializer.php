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
        $client = $this->client;
        $capabilities = $this->getCapabilities();

        $context->setBrowserInformations(function () use ($client, $capabilities) {
            return $client->createBrowser($capabilities);
        }, $this->baseUrl);
    }

    private function getCapabilities()
    {
        return $this->browserName;
    }
}
