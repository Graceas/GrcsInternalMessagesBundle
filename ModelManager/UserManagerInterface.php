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

use Grcs\InternalMessagesBundle\Model\ParticipantInterface;

/**
 * Interface to be implemented by user managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to user should happen through this interface.
 */
interface UserManagerInterface
{
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
     * @param int $userId
     * @return ParticipantInterface
     */
    function findUserById($userId);

    /**
     * Returns the message's fully qualified class MessageManagerInterface.
     *
     * @return string
     */
    function getClass();
}
