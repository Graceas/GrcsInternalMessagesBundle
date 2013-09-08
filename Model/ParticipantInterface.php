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

/**
 * A user participating to a thread.
 * May be implemented by a FOS\UserBundle user document or entity.
 * Or anything you use to represent users in the application.
 */
interface ParticipantInterface
{
    /**
     * Gets the unique identifier of the participant
     *
     * @return string
     */
    function getId();

    /**
     * Check can send message to user
     *
     * @param ParticipantInterface $user
     * @return bool
     */
    function isCanSendMessageToUser(ParticipantInterface $user);

    /**
     * Check can receive message from user
     *
     * @param ParticipantInterface $user
     * @return bool
     */
    function isCanReceiveMessageFromUser(ParticipantInterface $user);
}
