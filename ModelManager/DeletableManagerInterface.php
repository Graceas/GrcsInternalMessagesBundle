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

use Grcs\InternalMessagesBundle\Model\DeletableInterface;

/**
 * Capable of updating the delete state of objects directly in the storage,
 * without modifying the state of the object
 */
interface DeletableManagerInterface
{

    /**
     * Mark is deleted by sender
     * @param DeletableInterface $deletable
     * @return mixed
     */
    function markAsDeletedBySender(DeletableInterface $deletable);

    /**
     * Mark is deleted by recipient
     * @param DeletableInterface $deletable
     * @return mixed
     */
    function markAsDeletedByRecipient(DeletableInterface $deletable);

}
