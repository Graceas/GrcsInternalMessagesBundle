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

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface {
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('grcs_internal_messages', 'array');

        $rootNode
            ->children()
                ->arrayNode('entity')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('message_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('user_class')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('view')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('date_format')->defaultValue('Y/m/d H:i')->cannotBeEmpty()->end()
                        ->scalarNode('truncate_len')->defaultValue('50')->end()
                        ->scalarNode('knp_pagination_enable')->defaultValue(true)->end()
                        ->scalarNode('knp_pagination_limit_per_page')->defaultValue(30)->end()
                        ->scalarNode('sort_by_created')->defaultValue('desc')->isRequired()->end()
                        ->scalarNode('sort_by_is_read')->defaultValue('asc')->isRequired()->end()

                        ->arrayNode('templates')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('layout')->defaultValue('GrcsInternalMessagesBundle::layout.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('view')->defaultValue('GrcsInternalMessagesBundle::view.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('create')->defaultValue('GrcsInternalMessagesBundle::create.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('reply')->defaultValue('GrcsInternalMessagesBundle::reply.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('inbox')->defaultValue('GrcsInternalMessagesBundle::inbox.html.twig')->cannotBeEmpty()->end()
                                ->scalarNode('outbox')->defaultValue('GrcsInternalMessagesBundle::outbox.html.twig')->cannotBeEmpty()->end()
                            ->end()
                        ->end()

                        ->arrayNode('forms')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->arrayNode('new_message_form')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('factory')->defaultValue('grcs.internal_messages.new_message_form.factory')->cannotBeEmpty()->end()
                                        ->scalarNode('type')->defaultValue('grcs.internal_messages.new_message_form.type')->cannotBeEmpty()->end()
                                        ->scalarNode('handler')->defaultValue('grcs.internal_messages.new_message_form.handler')->cannotBeEmpty()->end()
                                        ->scalarNode('name')->defaultValue('message')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                                ->arrayNode('reply_form')
                                    ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode('factory')->defaultValue('grcs.internal_messages.reply_form.factory')->cannotBeEmpty()->end()
                                        ->scalarNode('type')->defaultValue('grcs.internal_messages.reply_form.type')->cannotBeEmpty()->end()
                                        ->scalarNode('handler')->defaultValue('grcs.internal_messages.reply_form.handler')->cannotBeEmpty()->end()
                                        ->scalarNode('name')->defaultValue('reply')->cannotBeEmpty()->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}