<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class HashidsExtension extends Extension
{
    private const string DIR_CONFIG = '/../Resources/config';
    private const string DIR_CONFIG_PARAM = self::DIR_CONFIG . '/parameters.yaml';

    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . self::DIR_CONFIG));
        $loader->load('services.yaml');
        $loader->load('twig.yaml');

        $this->createParameters($container, $config);
    }

    private function createParameters(ContainerBuilder $container, array $config): void
    {
        $parameters = Yaml::parseFile(__DIR__ . self::DIR_CONFIG_PARAM)['hashids']['parameters'] ?? [];

        foreach ($parameters as $parameter) {
            $container->setParameter("danilovl.hashids.{$parameter}", $config[$parameter]);
        }
    }

    public function getAlias(): string
    {
        return Configuration::ALIAS;
    }
}
