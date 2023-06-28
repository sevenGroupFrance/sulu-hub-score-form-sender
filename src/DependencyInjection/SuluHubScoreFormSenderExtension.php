<?php

namespace SevenGroupFrance\SuluHubScoreFormSenderBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class SuluHubScoreFormSenderExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sulu_hub_score_form_sender.base_configuration.id', $config['base_configuration']['id']);
        $container->setParameter('sulu_hub_score_form_sender.base_configuration.pwd', $config['base_configuration']['pwd']);
        $container->setParameter('sulu_hub_score_form_sender.base_configuration.base_url', $config['base_configuration']['base_url']);
        $container->setParameter('sulu_hub_score_form_sender.login_url', $config['login_url']);
        $container->setParameter('sulu_hub_score_form_sender.payload_configuration.campagn_id', $config['payload_configuration']['campagn_id']);
        $container->setParameter('sulu_hub_score_form_sender.payload_configuration.database_id', $config['payload_configuration']['database_id']);
        $container->setParameter('sulu_hub_score_form_sender.send_mail_url', $config['send_mail_url']);
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
}
