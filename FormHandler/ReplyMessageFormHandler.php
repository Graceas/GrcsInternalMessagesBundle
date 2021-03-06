<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\FormHandler;

use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Symfony\Component\Form\Exception\LogicException;

class ReplyMessageFormHandler extends AbstractMessageFormHandler
{
    public function preSaveValidation(MessageInterface $message)
    {
        if (!$message->getSender()) {
            throw new LogicException('Sender required.');
        }

        if (!$message->getRecipient()) {
            throw new LogicException('Recipient required.');
        }

        if (!$message->getParentMessage()) {
            throw new LogicException('Parent message required.');
        }

        if ($message->getSender() === $message->getRecipient()) {
            throw new LogicException('Sender and recipient identical.');
        }

        if ($message->getSender() !== $message->getParentMessage()->getRecipient()) {
            throw new LogicException('Sender not access for reply.');
        }

        if ($message->getRecipient() !== $message->getParentMessage()->getSender()) {
            throw new LogicException('Recipient not access for reply.');
        }
    }
}
