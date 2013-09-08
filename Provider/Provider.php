<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Provider;

use Grcs\InternalMessagesBundle\ModelManager\MessageManager;
use Grcs\InternalMessagesBundle\ModelManager\UserManager;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Grcs\InternalMessagesBundle\ModelManager\ThreadManagerInterface;
use Grcs\InternalMessagesBundle\Security\AuthorizerInterface;
use Grcs\InternalMessagesBundle\Security\ParticipantProviderInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Grcs\InternalMessagesBundle\ModelManager\MessageManagerInterface;

/**
 * Provides threads for the current authenticated user
 */
class Provider implements ProviderInterface
{
    /**
     * The message manager
     *
     * @var MessageManager
     */
    protected $messageManager;

    /**
     * The user manager
     *
     * @var UserManager
     */
    protected $userManager;

    /**
     * The authorizer manager
     *
     * @var authorizerInterface
     */
    protected $authorizer;

    /**
     * The participant provider instance
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    public function __construct(MessageManager $messageManager, AuthorizerInterface $authorizer,
                                ParticipantProviderInterface $participantProvider, UserManager $userManager)
    {
        $this->messageManager = $messageManager;
        $this->authorizer = $authorizer;
        $this->participantProvider = $participantProvider;
        $this->userManager = $userManager;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getInboxMessages()
     */
    public function getInboxMessages($getQuery = false, $createdAtOrder = 'desc', $isReadOrder = 'asc')
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->messageManager->getMessagesByParticipant($participant, $getQuery, $createdAtOrder, $isReadOrder);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getSentMessages()
     */
    public function getSentMessages($getQuery = false, $createdAtOrder = 'desc')
    {
        $participant = $this->getAuthenticatedParticipant();

        return $this->messageManager->getMessagesFromParticipant($participant, $getQuery, $createdAtOrder);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getMessage()
     */
    public function getMessage($messageId)
    {
        $message = $this->messageManager->findMessageById($messageId);

        if (!$message) {
            throw new NotFoundHttpException('There is no such message');
        }
        if (!$this->authorizer->canSeeMessage($message)) {
            throw new AccessDeniedException('You are not allowed to see this message');
        }

        if (!$message->getIsRead() && $message->isRecipient($this->getAuthenticatedParticipant())) {
            $this->messageManager->markAsRead($message);
            $this->messageManager->saveMessage($message, true);
        }

        return $message;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::deleteMessage()
     */
    public function deleteMessage($messageId)
    {
        $message = $this->messageManager->findMessageById($messageId);

        if (!$message) {
            throw new NotFoundHttpException('There is no such message');
        }
        if (!$this->authorizer->canSeeMessage($message)) {
            throw new AccessDeniedException('You are not allowed to see this message');
        }

        if ($message->isRecipient($this->getAuthenticatedParticipant())) {
            $this->messageManager->markAsDeletedByRecipient($message);
        } else {
            $this->messageManager->markAsDeletedBySender($message);
        }

        $this->messageManager->saveMessage($message, true);
        return $message;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getUser()
     */
    public function getUser($userId)
    {
        if ($this->getAuthenticatedParticipant()->getId() === $userId) {
            return $this->getAuthenticatedParticipant();
        }

        $user = $this->userManager->findUserById($userId);

        if (!$user) {
            throw new NotFoundHttpException('There is no such user');
        }

        return $user;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getRecipient()
     */
    public function getRecipient($userId)
    {
        $recipient = $this->getUser($userId);

        if ($recipient->getId() === $this->getAuthenticatedParticipant()->getId()) {
            throw new AccessDeniedException('You are not allowed to send this message');
        }

        if (method_exists($this->getAuthenticatedParticipant(), 'isCanSendMessageToUser')) {
            if (!$this->getAuthenticatedParticipant()->isCanSendMessageToUser($recipient)) {
                throw new AccessDeniedException('You are not allowed to send this message to it user');
            }
        }

        if (method_exists($recipient, 'isCanReceiveMessageFromUser')) {
            if (!$recipient->isCanReceiveMessageFromUser($this->getAuthenticatedParticipant())) {
                throw new AccessDeniedException('User not allowed to receive message from you');
            }
        }

        return $recipient;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getUnreadMessages()
     */
    public function getUnreadMessages()
    {
        return $this->messageManager->getUnreadMessageByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getTotalMessages()
     */
    public function getTotalMessages()
    {
        return $this->messageManager->getTotalMessageByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * @see Grcs\InternalMessagesBundle\Provider\ProviderInterface::getTotalOutMessages()
     */
    public function getTotalOutMessages()
    {
        return $this->messageManager->getTotalOutMessageByParticipant($this->getAuthenticatedParticipant());
    }

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    public function getAuthenticatedParticipant()
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
