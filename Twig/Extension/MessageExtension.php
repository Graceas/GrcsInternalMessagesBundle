<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Twig\Extension;

use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Security\ParticipantProviderInterface;
use Grcs\InternalMessagesBundle\Model\ReadableInterface;
use Grcs\InternalMessagesBundle\Provider\ProviderInterface;

class MessageExtension extends \Twig_Extension
{
    protected $participantProvider;
    protected $provider;

    protected $nbUnreadMessagesCache;
    protected $nbTotalMessagesCache;
    protected $nbTotalOutMessagesCache;

    public function __construct(ParticipantProviderInterface $participantProvider, ProviderInterface $provider)
    {
        $this->participantProvider = $participantProvider;
        $this->provider = $provider;
    }

    /**
     * Returns a list of global functions to add to the existing list.
     *
     * @return array An array of global functions
     */
    public function getFunctions()
    {
        return array(
            'grcs_internal_message_unread_count' => new \Twig_Function_Method($this, 'getNbUnread'),
            'grcs_internal_message_inbox_count' => new \Twig_Function_Method($this, 'getInboxCount'),
            'grcs_internal_message_outbox_count' => new \Twig_Function_Method($this, 'getOutboxCount'),
            'grcs_internal_message_allowed_actions' => new \Twig_Function_Method($this, 'getAllowedActions'),
        );
    }

    /**
     * Gets the number of unread messages for the current user
     *
     * @return int
     */
    public function getNbUnread()
    {
        if (null === $this->nbUnreadMessagesCache) {
            $this->nbUnreadMessagesCache = $this->provider->getUnreadMessages();
        }

        return $this->nbUnreadMessagesCache;
    }

    /**
     * Gets the number of messages for the current user
     *
     * @return int
     */
    public function getInboxCount()
    {
        if (null === $this->nbTotalMessagesCache) {
            $this->nbTotalMessagesCache = $this->provider->getTotalMessages();
        }

        return $this->nbTotalMessagesCache;
    }

    /**
     * Gets the number of posted messages for the current user
     *
     * @return int
     */
    public function getOutboxCount()
    {
        if (null === $this->nbTotalOutMessagesCache) {
            $this->nbTotalOutMessagesCache = $this->provider->getTotalOutMessages();
        }

        return $this->nbTotalOutMessagesCache;
    }

    public function getAllowedActions(MessageInterface $message)
    {
        return array(
            'view'   => $message->isSender($this->getAuthenticatedParticipant())
                || $message->isRecipient($this->getAuthenticatedParticipant()),
            'reply'  => $message->isRecipient($this->getAuthenticatedParticipant()),
            'delete' =>  $message->isSender($this->getAuthenticatedParticipant())
                || $message->isRecipient($this->getAuthenticatedParticipant())
        );
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

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'grcs_internal_messages';
    }
}
