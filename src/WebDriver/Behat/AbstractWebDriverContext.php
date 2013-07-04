<?php

namespace WebDriver\Behat;

use Behat\Behat\Context\BehatContext;

abstract class AbstractWebDriverContext extends BehatContext
{
    protected $baseUrl;
    protected $browserReference;
    protected $browser;

    public function setBrowserInformations($browserReference, $baseUrl)
    {
        if (!is_callable($browserReference)) {
            throw new \InvalidArgumentException(sprintf('Expected a callable, got a "%s".', is_object($browserReference) ? get_class($browserReference) : gettype($browserReference)));
        }

        $this->baseUrl = rtrim($baseUrl, '/');
        $this->browserReference = $browserReference;
        $this->browser = null;
    }

    public function getBrowser()
    {
        if (null === $this->browser) {
            if (null === $this->browserReference) {
                throw new \RuntimeException('Browser reference is missing from context.');
            }

            $this->browser = call_user_func($this->browserReference);
        }

        return $this->browser;
    }

    protected function getUrl($url)
    {
        if (!preg_match('#^https?://#', $url)) {
            $url = $this->baseUrl . '/'.ltrim($url, '/');
        }

        return $url;
    }
}
