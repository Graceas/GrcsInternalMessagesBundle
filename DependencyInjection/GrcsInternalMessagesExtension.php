<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class GrcsInternalMessagesExtension extends Extension {
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('grcs.internal_messages_config', $config);
        $container->setParameter('grcs.internal_messages.message_class', $config['entity']['message_class']);
        $container->setParameter('grcs.internal_messages.user_class', $config['entity']['user_class']);
        $container->setParameter('grcs.internal_messages.filter_class', $config['entity']['filter_class']);

        $container->setParameter('grcs.internal_messages.sorted.created', $config['view']['sort_by_created']);
        $container->setParameter('grcs.internal_messages.sorted.is_read', $config['view']['sort_by_is_read']);

        $container->setParameter('grcs.internal_messages.new_message_form.model', $config['entity']['message_class']);
        $container->setParameter('grcs.internal_messages.new_message_form.name', $config['view']['forms']['new_message_form']['name']);
        $container->setParameter('grcs.internal_messages.reply_form.model', $config['entity']['message_class']);
        $container->setParameter('grcs.internal_messages.reply_form.name', $config['view']['forms']['reply_form']['name']);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}