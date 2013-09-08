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

use Grcs\InternalMessagesBundle\Model\ReadableInterface;

/**
 * Capable of updating the read state of objects directly in the storage,
 * without modifying the state of the object
 */
interface ReadableManagerInterface
{
    /**
     * Marks the readable as read by this participant
     * Must be applied directly to the storage,
     * without modifying the readable state.
     * We want to show the unread readables on the page,
     * as well as marking them as read.
     *
     * @param ReadableInterface $readable
     */
    function markAsRead(ReadableInterface $readable);

    /**
     * Marks the readable as unread by this participant
     *
     * @param ReadableInterface $readable
     */
    function markAsUnread(ReadableInterface $readable);
}
