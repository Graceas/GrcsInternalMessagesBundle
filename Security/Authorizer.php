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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Grcs\InternalMessagesBundle\Security\ParticipantProviderInterface;

class Authorizer implements AuthorizerInterface
{
    /**
     * The participant provider
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    public function __construct(ParticipantProviderInterface $participantProvider)
    {
        $this->participantProvider = $participantProvider;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Security\AuthorizerInterface::canSeeMessage()
     */
    public function canSeeMessage(MessageInterface $message)
    {
        return $this->getAuthenticatedParticipant()
            && (
                $message->isRecipient($this->getAuthenticatedParticipant())
                || $message->isSender($this->getAuthenticatedParticipant())
            );
    }

    /**
     * @see Grcs\InternalMessagesBundle\Security\AuthorizerInterface::canDeleteMessage()
     */
    public function canDeleteMessage(MessageInterface $message)
    {
        return $this->canSeeMessage($message);
    }

    /**
     * @see Grcs\InternalMessagesBundle\Security\AuthorizerInterface::canMessageParticipant()
     */
    public function canMessageParticipant(ParticipantInterface $participant)
    {
        return true;
    }

    /**
     * Gets the current authenticated user
     *
     * @return ParticipantInterface
     */
    protected function getAuthenticatedParticipant()
    {
        return $this->participantProvider->getAuthenticatedParticipant();
    }
}
