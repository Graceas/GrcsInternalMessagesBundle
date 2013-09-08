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
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Grcs\InternalMessagesBundle\Security\ParticipantProviderInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Grcs\InternalMessagesBundle\ModelManager\MessageManager;

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

    public function __construct(Request $request, ParticipantProviderInterface $participantProvider, MessageManager $messageManager)
    {
        $this->request = $request;
        $this->participantProvider = $participantProvider;
        $this->messageManager = $messageManager;
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
