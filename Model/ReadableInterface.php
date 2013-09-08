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

interface ReadableInterface
{
    /**
     * Get is read by recipient
     *
     * @return boolean
     */
    public function getIsRead();

    /**
     * Set is read by recipient
     *
     * @param boolean $isRead
     * @return null
     */
    public function setIsRead($isRead);
}
