<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Controller;

use Grcs\InternalMessagesBundle\Model\TemporaryUser;
use Grcs\InternalMessagesBundle\Security\ParticipantProvider;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\Request;
use Grcs\InternalMessagesBundle\Provider\ProviderInterface;

class MessagesController extends ContainerAware {

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @param \Twig_Environment $twig
     */
    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get template by name from config
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    private function getTemplate($name)
    {
        $configs = $this->getConfigs();

        if (!isset($configs['view'])) {
            throw new \Exception('grcs_internal_messages.view required, try set it in config file');
        }

        if (!isset($configs['view']['templates'])) {
            throw new \Exception('grcs_internal_messages.view.templates required, try set it in config file');
        }

        if (!isset($configs['view']['templates'][$name])) {
            throw new \Exception('grcs_internal_messages.view.templates.' . $name . ' required, try set it in config file');
        }

        return $configs['view']['templates'][$name];
    }

    /**
     * @param $message_id
     * @return Response
     */
    public function viewAction($message_id)
    {
        $message = $this->getProvider()->getMessage($message_id);

        $html_output = $this->twig->render($this->getTemplate('view'),
            array(
                'configs'  => $this->getViewConfigs(),
                'message'  => $message,
                'reply'    => $message->getParentMessage()
            )
        );

        return new Response($html_output);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function inboxAction()
    {
        $request = $this->container->get('request');
        $view_configs = $this->getViewConfigs();

        $results = null;
        if ($view_configs['knp_pagination_enable']) {
            $paginator = $this->container->get('knp_paginator');
            $page = $request->query->get('page', 1);
            $results = $paginator->paginate(
                $this->getProvider()->getInboxMessages(true,
                    $view_configs['sort_by_created'], $view_configs['sort_by_is_read']),
                $page,
                $view_configs['knp_pagination_limit_per_page']
            );
        } else {
            $results = $this->getProvider()->getInboxMessages(false,
                $view_configs['sort_by_created'], $view_configs['sort_by_is_read']);
        }

        $html_output = $this->twig->render($this->getTemplate('inbox'),
            array(
                'configs'    => $view_configs,
                'results'    => $results
            )
        );

        return new Response($html_output);
    }

    /**
     * @return Response
     * @throws \Exception
     */
    public function outboxAction()
    {
        $request = $this->container->get('request');
        $view_configs = $this->getViewConfigs();

        $results = null;
        if ($view_configs['knp_pagination_enable']) {
            $paginator = $this->container->get('knp_paginator');
            $page = $request->query->get('page', 1);
            $results = $paginator->paginate(
                $this->getProvider()->getSentMessages(true, $view_configs['sort_by_created']),
                $page,
                $view_configs['knp_pagination_limit_per_page']
            );
        } else {
            $results = $this->getProvider()->getSentMessages(false, $view_configs['sort_by_created']);
        }

        $html_output = $this->twig->render($this->getTemplate('outbox'),
            array(
                'configs'    => $view_configs,
                'results'    => $results
            )
        );

        return new Response($html_output);
    }

    /**
     * @param $user_id
     * @return RedirectResponse|Response
     */
    public function createAction($user_id)
    {
        $view_configs = $this->getViewConfigs();

        $recipient = $this->getProvider()->getRecipient($user_id);

        $form = $this->container->get('grcs.internal_messages.new_message_form.factory')->create($recipient);
        $formHandler = $this->container->get('grcs.internal_messages.new_message_form.handler');

        $request = $this->container->get('request');
        $is_ajax = $request->isXmlHttpRequest();

        if ($request->getMethod() == 'POST') {
            if ($message = $formHandler->process($form)) {
                if ($is_ajax) {

                    $html_output = $this->twig->render($this->getTemplate('create_ajax_success'),
                        array(
                            'message'    => $message
                        )
                    );

                    return new Response(json_encode(array(
                        'status' => 'data',
                        'data' => $html_output
                    )));
                }

                return new RedirectResponse($this->container->get('router')->generate('grcs_internal_messages_view', array(
                    'message_id' => $message->getId()
                )));
            } else {
                if ($is_ajax) {
                    $errors = $this->container->get('mxup.form_serializer')->serializeFormErrors($form, true, true);

                    return new Response(json_encode(array(
                        'status' => 'error',
                        'errors' => $errors
                    )));
                }
            }
        }

        $html_output = $this->twig->render($this->getTemplate('create' . (($is_ajax) ? '_ajax' : '')),
            array(
                'configs'    => $view_configs,
                'form'       => $form->createView(),
                'user_id'    => $user_id
            )
        );

        if ($is_ajax) {
            return new Response(json_encode(array(
                'status' => 'data',
                'data' => $html_output
            )));
        }

        return new Response($html_output);
    }

    /**
     * @param $message_id
     * @return RedirectResponse|Response
     */
    public function replyAction($message_id)
    {
        $view_configs = $this->getViewConfigs();

        $message = $this->getProvider()->getMessage($message_id);

        $form = $this->container->get('grcs.internal_messages.reply_message_form.factory')->create($message);
        $formHandler = $this->container->get('grcs.internal_messages.reply_message_form.handler');

        if ($message = $formHandler->process($form)) {
            return new RedirectResponse($this->container->get('router')->generate('grcs_internal_messages_view', array(
                'message_id' => $message->getId()
            )));
        }

        $html_output = $this->twig->render($this->getTemplate('reply'),
            array(
                'configs'    => $view_configs,
                'form'       => $form->createView(),
                'message_id' => $message_id
            )
        );

        return new Response($html_output);
    }

    public function deleteAction($message_id)
    {
        $request = $this->container->get('request');
        $message = $this->getProvider()->deleteMessage($message_id);

        $request->getSession()->getFlashBag()->add('message-notice',
            $this->container->get('translator')->trans('Message deleted successfully.'));

        if ($message->isSender($this->getProvider()->getAuthenticatedParticipant())) {
            return new RedirectResponse($this->container->get('router')->generate('grcs_internal_messages_outbox'));
        } else {
            return new RedirectResponse($this->container->get('router')->generate('grcs_internal_messages_inbox'));
        }
    }

    /**
     * Gets the provider service
     *
     * @return ProviderInterface
     */
    protected function getProvider()
    {
        /* @var $provider ProviderInterface */
        $provider = $this->container->get('grcs.internal_messages.provider');
        return $provider;
    }

    /**
     * Get config array
     * @return array
     */
    protected function getConfigs()
    {
        $configs = $this->container->get('service_container')->getParameter('grcs.internal_messages_config');
        return $configs;
    }

    /**
     * Gets view configs array
     *
     * @return array
     */
    protected function getViewConfigs()
    {
        $configs = $this->getConfigs();
        return $configs['view'];
    }

}