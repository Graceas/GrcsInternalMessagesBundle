<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Entity;

use Grcs\InternalMessagesBundle\Model\Message as BaseMessage;
use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;

abstract class Message extends BaseMessage
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isRead = false;
        $this->isDeleted = false;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getCreatedAt()
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setCreatedAt()
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getBody()
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setBody()
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getSender()
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setSender()
     */
    public function setSender(ParticipantInterface $sender)
    {
        $this->sender = $sender;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getRecipient()
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setRecipient()
     */
    public function setRecipient(ParticipantInterface $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getParentMessage()
     */
    function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setParentMessage()
     */
    function setParentMessage(MessageInterface $parentMessage)
    {
        $this->parentMessage = $parentMessage;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getIsRead()
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setIsRead()
     */
    public function setIsRead($isRead)
    {
        $this->isRead = (boolean)$isRead;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getIsDeletedBySender()
     */
    public function getIsDeletedBySender()
    {
        return $this->isDeletedBySender;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::getIsDeletedByRecipient()
     */
    public function getIsDeletedByRecipient()
    {
        return $this->isDeletedByRecipient;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setIsDeletedBySender()
     */
    public function setIsDeletedBySender($isDeleted)
    {
        $this->isDeletedBySender = (boolean)$isDeleted;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::setIsDeletedByRecipient()
     */
    public function setIsDeletedByRecipient($isDeleted)
    {
        $this->isDeletedByRecipient = (boolean)$isDeleted;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::isRecipient()
     */
    public function isRecipient(ParticipantInterface $recipient)
    {
        return $this->recipient === $recipient;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\MessageInterface::isSender()
     */
    public function isSender(ParticipantInterface $recipient)
    {
        return $this->sender === $recipient;
    }

    /**
     * Gets the created at timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->getCreatedAt()->getTimestamp();
    }
}
