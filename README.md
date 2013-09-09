GrcsInternalMessages - The Internal Messages System for your Symfony 2 application
==================================================================================

Inspired by FOSMessageBundle (http://github.com/FriendsOfSymfony/FOSMessageBundle)

License:

    see LICENSE


Installation
============

1) Add InternalMessagesBundle to your vendor/ dir

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

    // src/Your/Bundle/Entity/YourMessages.php
    // ...
    use Grcs\InternalMessagesBundle\Entity\Message as BaseMessages;
    // ...
    class YourMessages extends BaseMessages {
        // ...
        and implement all parent methods
        // ...
    }

OR

    Copy Entity from vendor/grcs/internal-messages-bundle/Grcs/InternalMessagesBundle/Entity/YourMessage.php
    to your Entity folder (src/Your/Bundle/Entity/YourMessages.php).
    Change namespace Your\EntityNamespace\Entity and class name.
    Change User targetEntity for $sender and $recipient. (Your\EntityNamespace\Entity\User)
    Change Message targetEntity for $parentMessage. (Your\EntityNamespace\Entity\YourMessage)

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

