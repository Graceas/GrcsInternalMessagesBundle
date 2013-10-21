<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Model;

use Grcs\InternalMessagesBundle\Model\ParticipantInterface;

/**
 * Message model
 */
interface MessageInterface
{
    /**
     * Gets the message unique id
     *
     * @return mixed
     */
    function getId();

    /**
     * Get created at date
     *
     * @return DateTime
     */
    function getCreatedAt();

    /**
     * Set created at
     *
     * @param  DateTime
     * @return null
     */
    function setCreatedAt($createdAt);

    /**
     * Get message body
     *
     * @return string
     */
    function getBody();

    /**
     * Set message body
     *
     * @param  string
     * @return null
     */
    function setBody($body);

    /**
     * Get original message body
     *
     * @return string
     */
    function getOriginalBody();

    /**
     * Set original message body
     *
     * @param  string
     * @return null
     */
    function setOriginalBody($body);

    /**
     * Get message sender
     *
     * @return ParticipantInterface
     */
    function getSender();

    /**
     * Tells if the user participates to the conversation
     *
     * @param  ParticipantInterface
     * @return bool
     */
    function isSender(ParticipantInterface $recipient);

    /**
     * Set message sender
     *
     * @param  ParticipantInterface
     * @return null
     */
    function setSender(ParticipantInterface $sender);

    /**
     * Get message recipient
     *
     * @return ParticipantInterface
     */
    function getRecipient();

    /**
     * Tells if the user participates to the conversation
     *
     * @param  ParticipantInterface
     * @return bool
     */
    function isRecipient(ParticipantInterface $recipient);

    /**
     * Set message recipient
     *
     * @param  ParticipantInterface
     * @return null
     */
    function setRecipient(ParticipantInterface $recipient);

    /**
     * Get reply for message
     *
     * @return MessageInterface
     */
    function getParentMessage();

    /**
     * Set reply for message
     *
     * @param  MessageInterface
     * @return null
     */
    function setParentMessage(MessageInterface $parentMessage);
}
