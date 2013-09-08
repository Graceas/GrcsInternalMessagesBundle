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
class NewMessageFormFactory extends AbstractMessageFormFactory
{
    /**
     * Creates a new message form
     *
     * @param $recipient
     * @return \Symfony\Component\Form\FormInterface
     */
    public function create($recipient)
    {
        $message = $this->createModelInstance();
        $message->setRecipient($recipient);

        return $this->formFactory->createNamed($this->formName, $this->formType, $message);
    }
}
