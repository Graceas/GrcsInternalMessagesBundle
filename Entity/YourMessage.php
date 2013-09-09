<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Your\EntityNamespace\Entity;

use Doctrine\ORM\Mapping as ORM;
use Grcs\InternalMessagesBundle\Entity\Message as BaseMessages;

/**
 * YourMessage
 *
 * @ORM\Table(name="your_messages")
 * @ORM\Entity
 */
class YourMessage extends BaseMessages {

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::sender
     *
     * @ORM\ManyToOne(targetEntity="Your\EntityNamespace\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     */
    protected $sender;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::recipient
     *
     * @ORM\ManyToOne(targetEntity="Your\EntityNamespace\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    protected $recipient;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::body
     *
     * @ORM\Column(name="body", type="text", nullable=false)
     */
    protected $body;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::createdAt
     *
     * @ORM\Column(name="created", type="datetime")
     */
    protected $createdAt;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::parentMessage
     *
     * @ORM\OneToOne(targetEntity="Your\EntityNamespace\Entity\YourMessage")
     * @ORM\JoinColumn(name="parent_message_id", referencedColumnName="id")
     */
    protected $parentMessage;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::isRead
     *
     * @ORM\Column(name="is_read", type="boolean", nullable=false)
     */
    protected $isRead;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::isDeletedBySender
     *
     * @ORM\Column(name="is_deleted_by_sender", type="boolean", nullable=false)
     */
    protected $isDeletedBySender;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::isDeletedByRecipient
     *
     * @ORM\Column(name="is_deleted_by_recipient", type="boolean", nullable=false)
     */
    protected $isDeletedByRecipient;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->isRead = false;
        $this->isDeletedBySender = false;
        $this->isDeletedByRecipient = false;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getId()
     */
    public function getId()
    {
        return parent::getId();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getCreatedAt()
     */
    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::setCreatedAt()
     */
    public function setCreatedAt($createdAt)
    {
        parent::setCreatedAt($createdAt);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getBody()
     */
    public function getBody()
    {
        return parent::getBody();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::setBody()
     */
    public function setBody($body)
    {
        parent::setBody($body);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getSender()
     */
    public function getSender()
    {
        return parent::getSender();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::setSender()
     */
    public function setSender(\Grcs\InternalMessagesBundle\Model\ParticipantInterface $sender)
    {
        parent::setSender($sender);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getRecipient()
     */
    public function getRecipient()
    {
        return parent::getRecipient();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::setRecipient()
     */
    public function setRecipient(\Grcs\InternalMessagesBundle\Model\ParticipantInterface $recipient)
    {
        parent::setRecipient($recipient);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::getParentMessage()
     */
    function getParentMessage()
    {
        return parent::getParentMessage();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Message::setParentMessage()
     */
    function setParentMessage(\Grcs\InternalMessagesBundle\Model\MessageInterface $parentMessage)
    {
        parent::setParentMessage($parentMessage);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::getIsRead()
     */
    public function getIsRead()
    {
        return parent::getIsRead();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::setIsRead()
     */
    public function setIsRead($isRead)
    {
        parent::setIsRead($isRead);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::getIsDeletedBySender()
     */
    public function getIsDeletedBySender()
    {
        return parent::getIsDeletedBySender();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::getIsDeletedByRecipient()
     */
    public function getIsDeletedByRecipient()
    {
        return parent::getIsDeletedByRecipient();
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::setIsDeletedBySender()
     */
    public function setIsDeletedBySender($isDeleted)
    {
        parent::setIsDeletedBySender($isDeleted);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\Message::setIsDeletedByRecipient()
     */
    public function setIsDeletedByRecipient($isDeleted)
    {
        parent::setIsDeletedByRecipient($isDeleted);
    }
}