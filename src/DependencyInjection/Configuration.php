<?php

namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class Configuration extends NodeBuilder implements ConfigurationInterface 
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sulu_hub_score_form_sender');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('login')
                    ->children()
                        ->scalarNode('id')
                            ->defaultValue('')
                        ->end()
                        ->scalarNode('pwd')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('forms')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                        ->children()
                            ->arrayNode('config')
                                ->children()
                                    ->scalarNode('campaign_id')->end()
                                    ->scalarNode('database_id')->end()
                                ->end()
                            ->end()
                            ->arrayNode('fields')
                                ->prototype('scalar')->end()
                            ->end()
                            ->arrayNode('messages')
                                ->children()
                                    ->scalarNode('error')->end()
                                    ->scalarNode('success')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
