<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Security;

use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;

/**
 * Manages permissions to manipulate messages
 */
interface AuthorizerInterface
{
    /**
     * Tells if the current user is allowed
     * to see this thread
     *
     * @param MessageInterface $message
     * @return boolean
     */
    function canSeeMessage(MessageInterface $message);

    /**
     * Tells if the current participant is allowed
     * to delete this thread
     *
     * @param MessageInterface $message
     * @return boolean
     */
    function canDeleteMessage(MessageInterface $message);

    /**
     * Tells if the current participant is allowed
     * to send a message to this other participant
     *
     * @param ParticipantInterface $participant the one we want to send a message to
     * @return boolean
     */
    function canMessageParticipant(ParticipantInterface $participant);
}
