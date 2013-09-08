GrcsInternalMessages - The Internal Messages System for your Symfony 2 application
==================================================================================

License:

    see LICENSE


Installation
============

1) Add InternalMessagesBundle to your vendor/ dir

Through submodules:

$ git submodule add git://github.com/FriendsOfSymfony/FOSMessageBundle.git vendor/bundles/FOS/MessageBundle


Through composer:

    "require": {
        ...
        "grcs/internal-messages-bundle": "dev-master"
        ...
    }

2) Add InternalMessagesBundle to your application kernel

    // app/AppKernel.php

    public function registerBundles()
    {
        return array(
            // ...
            new Grcs\InternalMessagesBundle\GrcsInternalMessagesBundle(),
            // ...
        );
    }

3) Configure your project

    # app/config/config.yml

    grcs_internal_messages:
        entity:
            message_class:     'Grcs\FrontendBundle\Entity\InternalMessages'
            user_class:        'Grcs\SecurityBundle\Entity\User'
        view:
            date_format:     'Y/m/d H:i'           #format for createdAt DateTime
            truncate_len:    50                    #(int|false) truncate text body in the list
            sort_by_created: 'desc'                #(asc|desc|false)
            sort_by_is_read: false                 #(asc|desc|false)
            knp_pagination_enable: true            #(true|false) enable knp pagination on the page
            knp_pagination_limit_per_page: 30      #(int|null) knp pagination limit per page
            templates:
                layout: 'GrcsInternalMessagesBundle::layout.html.twig'
                view:   'GrcsInternalMessagesBundle::view.html.twig'
                create: 'GrcsInternalMessagesBundle::create.html.twig'
                reply:  'GrcsInternalMessagesBundle::reply.html.twig'
                inbox:  'GrcsInternalMessagesBundle::inbox.html.twig'
                outbox: 'GrcsInternalMessagesBundle::outbox.html.twig'
            forms:
                new_message_form:
                    factory:    'grcs.internal_messages.new_message_form.factory'
                    type:       'grcs.internal_messages.new_message_form.type'
                    handler:    'grcs.internal_messages.new_message_form.handler'
                    name:       'message'
                reply_form:
                    factory:    'grcs.internal_messages.reply_form.factory'
                    type:       'grcs.internal_messages.reply_form.type'
                    handler:    'grcs.internal_messages.reply_form.handler'
                    name:       'reply'


4) Change your 'user_class'

    // for example (!) src/Grcs/SecurityBundle/Entity/User.php
    use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
    class User implements ParticipantInterface
    {
    // ...
        public function isCanSendMessageToUser(ParticipantInterface $user)
        {
            // you can set 'can send message' logic here
            return true;
        }

        public function isCanReceiveMessageFromUser(ParticipantInterface $user)
        {
            // you can set 'can receive message' logic here
            return true;
        }
    // ...
    }

5) Create/modify your 'message_class'. You may add another fields or methods to model class.

    // src/Grcs/FrontendBundle/Entity/InternalMessages.php
    // ...
    use Grcs\InternalMessagesBundle\Entity\Message as BaseMessages;
    // ...
    class InternalMessages extends BaseMessages {

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::id
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::sender
         *
         * @ORM\ManyToOne(targetEntity="Grcs\SecurityBundle\Entity\User", inversedBy="messages")
         * @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
         */
        protected $sender;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::recipient
         *
         * @ORM\ManyToOne(targetEntity="Grcs\SecurityBundle\Entity\User", inversedBy="messages")
         * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
         */
        protected $recipient;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::body
         *
         * @ORM\Column(name="body", type="text", nullable=false)
         */
        protected $body;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::createdAt
         *
         * @ORM\Column(name="created", type="datetime")
         */
        protected $createdAt;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::parentMessage
         *
         * @ORM\OneToOne(targetEntity="InternalMessages")
         * @ORM\JoinColumn(name="parent_message_id", referencedColumnName="id")
         */
        protected $parentMessage;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::isRead
         *
         * @ORM\Column(name="is_read", type="boolean", nullable=false)
         */
        protected $isRead;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::isDeletedBySender
         *
         * @ORM\Column(name="is_deleted_by_sender", type="boolean", nullable=false)
         */
        protected $isDeletedBySender;

        /**
         * @see Grcs\InternalMessagesBundle\Entity\Message::isDeletedByRecipient
         *
         * @ORM\Column(name="is_deleted_by_recipient", type="boolean", nullable=false)
         */
        protected $isDeletedByRecipient;

        /**
         * Constructor.
         */
        public function __construct()
        {
            $this->createdAt = new \DateTime();
            $this->isRead = false;
            $this->isDeletedBySender = false;
            $this->isDeletedByRecipient = false;
        }
        public function getId()
        {
            return parent::getId();
        }
        public function getCreatedAt()
        {
            return parent::getCreatedAt();
        }
        public function setCreatedAt($createdAt)
        {
            parent::setCreatedAt($createdAt);
        }
        public function getBody()
        {
            return parent::getBody();
        }
        public function setBody($body)
        {
            parent::setBody($body);
        }
        public function getSender()
        {
            return parent::getSender();
        }
        public function setSender(\Grcs\InternalMessagesBundle\Model\ParticipantInterface $sender)
        {
            parent::setSender($sender);
        }
        public function getRecipient()
        {
            return parent::getRecipient();
        }
        public function setRecipient(\Grcs\InternalMessagesBundle\Model\ParticipantInterface $recipient)
        {
            parent::setRecipient($recipient);
        }
        public function getParentMessage()
        {
            return parent::getParentMessage();
        }
        public function setParentMessage(\Grcs\InternalMessagesBundle\Model\MessageInterface $parentMessage)
        {
            parent::setParentMessage($parentMessage);
        }
        public function getIsRead()
        {
            return parent::getIsRead();
        }
        public function setIsRead($isRead)
        {
            parent::setIsRead($isRead);
        }
        public function getIsDeletedBySender()
        {
            return parent::getIsDeletedBySender();
        }
        public function getIsDeletedByRecipient()
        {
            return parent::getIsDeletedByRecipient();
        }
        public function setIsDeletedBySender($isDeleted)
        {
            parent::setIsDeletedBySender($isDeleted);
        }
        public function setIsDeletedByRecipient($isDeleted)
        {
            parent::setIsDeletedByRecipient($isDeleted);
        }
    }

6) Register routing

You will probably want to include the built-in routes.

In YAML:

    # app/config/routing.yml

    internal_messages:
        resource: '@GrcsInternalMessagesBundle/Resources/config/routing.xml'
        prefix: /optional_routing_prefix

Or if you prefer XML::

    # app/config/routing.xml

    <import resource="@GrcsInternalMessagesBundle/Resources/config/routing.xml"/>


Templating
==========

InternalMessagesBundle provides a few twig functions::

    {# template.html.twig #}

    {# Get the number of new messages for the authenticated user #}
    You have {{ grcs_internal_message_unread_count() }} new messages

    {# Get the number of total messages in the inbox for the authenticated user #}
    You have {{ grcs_internal_message_inbox_count() }} messages it the inbox

    {# Get the number of total messages in the outbox for the authenticated user #}
    You have {{ grcs_internal_message_outbox_count() }} messages it the outbox

    {# Get allowed actions list for the message of this authenticated user #}
    {% for action, value in grcs_internal_message_allowed_actions(message) %}
        <a href="{{ path('grcs_internal_messages_' ~ action, { 'message_id' : message.id }) }}">
            {{ action|trans() }}
        </a>
    {% endfor %}

