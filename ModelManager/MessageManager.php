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

/**
 * Abstract Message Manager implementation which can be used as base by
 * your concrete manager.
 */
abstract class MessageManager implements MessageManagerInterface, ReadableManagerInterface, DeletableManagerInterface
{
    /**
     * Creates an empty message instance
     *
     * @return MessageInterface
     */
    public function createMessage()
    {
        $class = $this->getClass();
        $message = new $class;

        return $message;
    }
}
