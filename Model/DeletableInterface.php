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

interface DeletableInterface
{
    /**
     * Get is deleted by sender
     *
     * @return boolean
     */
    public function getIsDeletedBySender();

    /**
     * Set is deleted by sender
     *
     * @param boolean $isDeleted
     * @return null
     */
    public function setIsDeletedBySender($isDeleted);

    /**
     * Get is deleted by recipient
     *
     * @return boolean
     */
    public function getIsDeletedByRecipient();

    /**
     * Set is deleted by recipient
     *
     * @param boolean $isDeleted
     * @return null
     */
    public function setIsDeletedByRecipient($isDeleted);
}
