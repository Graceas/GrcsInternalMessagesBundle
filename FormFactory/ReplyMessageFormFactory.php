<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\FormFactory;

/**
 * Instantiates message forms
 */
class ReplyMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a reply message form
     *
     * @param $replyMessage
     * @return \Symfony\Component\Form\FormInterface
     */
    public function create($replyMessage)
    {
        $message = $this->createModelInstance();
        $message->setParentMessage($replyMessage);
        $message->setRecipient($replyMessage->getSender());

        return $this->formFactory->createNamed($this->formName, $this->formType, $message);
    }
}
