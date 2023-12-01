<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const string ALIAS = 'danilovl_hashids';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(self::ALIAS);
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->scalarNode('salt')
                    ->defaultValue('')
                ->end()
                ->integerNode('min_hash_length')
                    ->defaultValue(0)
                    ->min(0)
                ->end()
                ->scalarNode('alphabet')
                    ->defaultValue('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
                ->end()
                ->booleanNode('enable_param_converter')
                    ->defaultFalse()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
