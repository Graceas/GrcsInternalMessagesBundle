<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\FormHandler;

use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Model\FilterInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Grcs\InternalMessagesBundle\Security\ParticipantProviderInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Grcs\InternalMessagesBundle\ModelManager\MessageManager;
use Grcs\InternalMessagesBundle\ModelManager\FilterManager;

/**
 * Handles messages forms, from binding request to sending the message
 */
abstract class AbstractMessageFormHandler
{
    protected $request;

    /**
     * The participant provider instance
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * The message manager
     *
     * @var MessageManager
     */
    protected $messageManager;

    /**
     * The filter manager
     *
     * @var FilterManager
     */
    protected $filterManager;

    public function __construct(Request $request, ParticipantProviderInterface $participantProvider,
                                MessageManager $messageManager, FilterManager $filterManager)
    {
        $this->request = $request;
        $this->participantProvider = $participantProvider;
        $this->messageManager = $messageManager;
        $this->filterManager = $filterManager;
    }

    /**
     * Processes the form with the request
     *
     * @param Form $form
     * @return Message|false the sent message if the form is bound and valid, false otherwise
     */
    public function process(Form $form)
    {
        if ($this->request->getMethod() !== 'POST') {
            return false;
        }

        $form->handleRequest($this->request);

        if ($form->isValid()) {
            return $this->processValidForm($form);
        }

        return false;
    }

    /**
     * Processes the valid form, sends the message
     *
     * @param Form $form
     * @return MessageInterface the sent message
     */
    public function processValidForm(Form $form)
    {
        $message = $form->getData();
        $message->setSender($this->getAuthenticatedParticipant());

        $this->messageManager->refresh($message->getSender());
        if ($message->getParentMessage()) {
            $this->messageManager->refresh($message->getRecipient());
        }

        $this->preSaveValidation($message);

        if ($this->filterManager->isEnabled())
        {
            $messageBody = $message->getBody();
            $filters = $this->filterManager->getFilters();

            foreach ($filters as $filter) {
                $messageBody = $filter->filter($messageBody);
            }

            $message->setBody($messageBody);
        }

        $this->messageManager->saveMessage($message, true);

        return $message;
    }

    /**
     * Validate a message from the form data
     *
     * @param MessageInterface $message
     * @return MessageInterface the composed message ready to be save
     */
    abstract protected function preSaveValidation(MessageInterface $message);

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant()
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
