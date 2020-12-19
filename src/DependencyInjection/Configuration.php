<?php declare(strict_types=1);

namespace Danilovl\HashidsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\{
    TreeBuilder,
    NodeParentInterface
};
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const TREE_KEY_NAME = 'danilovl_hashids';

    public function getConfigTreeBuilder(): NodeParentInterface
    {
        $treeBuilder = new TreeBuilder(self::TREE_KEY_NAME);
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
                ->booleanNode('continue_next_converter')
                    ->defaultFalse()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
