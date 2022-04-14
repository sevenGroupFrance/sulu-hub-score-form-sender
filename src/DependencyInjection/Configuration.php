<?php

// src/Acme/SocialBundle/DependencyInjection/Configuration.php
namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('sulu_hub_score_send_form');

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
            ->end()
        ;

        return $treeBuilder;
    }
}
