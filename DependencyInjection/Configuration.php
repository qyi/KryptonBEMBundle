<?php

namespace Krypton\BEMBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Process\ExecutableFinder;

/**
 * This class contains the configuration information for the bundle
 *
 * @author Andrey Linko <su2ny@mail.ru>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $finder = new ExecutableFinder();

        $root = $treeBuilder->root('krypton_bem');

        $root
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifTrue(function ($v) { return true; })
                ->then(function ($v) {
                    if (!isset($v['levels'])) {
                        $v['levels'] = array();
                    } else if (is_string($v['levels'])) {
                        $v['levels'] = array($v['levels']);
                    }
                    return $v;
                })
            ->end()
            ->children()
                ->scalarNode('node_modules')->defaultValue('/usr/local/lib/node_modules')->end()
                ->scalarNode('bem_bl')->end()
                ->arrayNode('levels')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('filters')
                    ->children()
                        ->arrayNode('bem')
                            ->children()
                                ->scalarNode('bin')->defaultValue(function() use($finder) { return $finder->find('bem', '/usr/bin/bem'); })->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}