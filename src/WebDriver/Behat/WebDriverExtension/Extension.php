<?php

namespace WebDriver\Behat\WebDriverExtension;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Behat\Behat\Extension\ExtensionInterface;

/**
 * Mink extension for WebDriver manipulation.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Extension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.xml');

        $container->setParameter('behat.webdriver.client.url', $config['url']);
        $container->setParameter('behat.webdriver.base_url', $config['base_url']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->scalarNode('url')
                    ->defaultValue('http://localhost:4444/wd/hub')
                ->end()
                ->scalarNode('base_url')
                    ->defaultValue('http://localhost')
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return array();
    }

    protected function loadEnvironmentConfiguration()
    {
        $config = array();

        if ($url = getenv('WEBDRIVER_URL')) {
            $config['url'] = $url;
        }

        return $config;
    }
}
