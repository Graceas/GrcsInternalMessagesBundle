<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\ModelManager;

use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;

/**
 * Interface to be implemented by message managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to messages should happen through this interface.
 */
interface MessageManagerInterface
{
    /**
     * Tells how many unread messages this participant has
     *
     * @param ParticipantInterface $participant
     * @return int the number of unread messages
     */
    function getUnreadMessageByParticipant(ParticipantInterface $participant);

    /**
     * Tells how many messages this participant has
     *
     * @param ParticipantInterface $participant
     * @return int the number of total messages
     */
    function getTotalMessageByParticipant(ParticipantInterface $participant);

    /**
     * Tells how many messages this participant posted
     *
     * @param ParticipantInterface $participant
     * @return int the number of total posted messages
     */
    function getTotalOutMessageByParticipant(ParticipantInterface $participant);

    /**
     * Get messages (or query) to this participant
     *
     * @param ParticipantInterface $participant
     * @param bool $getQuery
     * @param mixed $createdAtOrder
     * @param mixed $isReadOrder
     * @return array messages
     */
    function getMessagesByParticipant(ParticipantInterface $participant, $getQuery = false,
                                      $createdAtOrder = 'desc', $isReadOrder = 'asc');

    /**
     * Get messages (or query) from this participant
     *
     * @param ParticipantInterface $participant
     * @param bool $getQuery
     * @param mixed $createdAtOrder
     * @return array messages
     */
    function getMessagesFromParticipant(ParticipantInterface $participant, $getQuery = false, $createdAtOrder = 'desc');

    /**
     * Creates an empty message instance
     *
     * @return MessageInterface
     */
    function createMessage();

    /**
     * Saves a message
     *
     * @param MessageInterface $message
     * @param Boolean $andFlush Whether to flush the changes (default true)
     */
    function saveMessage(MessageInterface $message, $andFlush = true);

    /**
     * Refresh object
     *
     * @param $object
     * @return mixed
     */
    function refresh($object);

    /**
     * Find message by ID
     *
     * @param int $messageId
     * @return MessageInterface
     */
    function findMessageById($messageId);

    /**
     * Returns the message's fully qualified class MessageManagerInterface.
     *
     * @return string
     */
    function getClass();
}
