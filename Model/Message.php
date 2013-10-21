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

/**
 * Abstract message model
 */
abstract class Message implements MessageInterface, ReadableInterface, DeletableInterface
{
    /**
     * Unique id of the message
     *
     * @var mixed
     */
    protected $id;

    /**
     * User who sent the message
     *
     * @var ParticipantInterface
     */
    protected $sender;

    /**
     * User who receive the message
     *
     * @var ParticipantInterface
     */
    protected $recipient;

    /**
     * Text body of the message
     *
     * @var string
     */
    protected $body;

    /**
     * Original text body of the message
     *
     * @var string
     */
    protected $originalBody;

    /**
     * Date when the message was sent
     *
     * @var DateTime
     */
    protected $createdAt;

    /**
     * Reply for message
     *
     * @var MessageInterface
     */
    protected $parentMessage;

    /**
     * Is read for recipient
     *
     * @var bool
     */
    protected $isRead;

    /**
     * Is deleted by sender
     *
     * @var bool
     */
    protected $isDeletedBySender;

    /**
     * Is deleted by recipient
     *
     * @var bool
     */
    protected $isDeletedByRecipient;
}
