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

/**
 * Provides threads for the current authenticated user
 */
interface ProviderInterface
{
    /**
     * Gets the messages in the inbox of the current user
     *
     * @param bool $getQuery
     * @param mixed $createdAtOrder
     * @param mixed $isReadOrder
     * @return MessageInterface
     */
    function getInboxMessages($getQuery = false, $createdAtOrder, $isReadOrder);

    /**
     * Gets the messages in the sentbox of the current user
     *
     * @param bool $getQuery
     * @param mixed $createdAtOrder
     * @return array of MessageInterface
     */
     function getSentMessages($getQuery = false, $createdAtOrder);

    /**
     * Gets a message by its ID
     * Performs authorization checks
     * Marks the message as read
     *
     * @return MessageInterface
     * @param int $messageId
     * @return MessageInterface
     */
    function getMessage($messageId);

    /**
     * Gets a message by its ID
     * Performs authorization checks
     * Marks the message as delete
     *
     * @return MessageInterface
     * @param int $messageId
     * @return MessageInterface
     */
    function deleteMessage($messageId);

    /**
     * Tells how many unread messages the authenticated participant has
     *
     * @return int the number of unread messages
     */
    function getUnreadMessages();

    /**
     * Tells how many messages the authenticated participant has
     *
     * @return int the number of messages
     */
    function getTotalMessages();

    /**
     * Tells how many messages the authenticated participant posted
     *
     * @return int the number of posted messages
     */
    function getTotalOutMessages();

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    function getAuthenticatedParticipant();

    /**
     * Gets the user by id
     *
     * @param int $userId
     * @return ParticipantInterface
     */
    function getUser($userId);

    /**
     * Gets the user by id
     * Performs can send checks
     *
     * @param int $userId
     * @return ParticipantInterface
     */
    function getRecipient($userId);
}
