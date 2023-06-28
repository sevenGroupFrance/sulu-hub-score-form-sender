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
                ->arrayNode('base_configuration')
                    ->children()
                        ->scalarNode('id')
                            ->defaultValue('')
                        ->end()
                        ->scalarNode('pwd')
                            ->defaultValue('')
                        ->end()
                        ->scalarNode('base_url')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('login_url')
                    ->defaultValue('')
                ->end()
                ->arrayNode('payload_configuration')
                    ->children()
                        ->scalarNode('campagn_id')
                            ->defaultValue('')
                        ->end()
                        ->scalarNode('database_id')
                            ->defaultValue('')
                        ->end()
                    ->end()
                ->end()
                ->scalarNode('send_mail_url')
                    ->defaultValue('')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
