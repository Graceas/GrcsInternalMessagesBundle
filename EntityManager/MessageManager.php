<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\EntityManager;

use Grcs\InternalMessagesBundle\Model\DeletableInterface;
use Grcs\InternalMessagesBundle\ModelManager\MessageManager as BaseMessageManager;
use Doctrine\ORM\EntityManager;
use Grcs\InternalMessagesBundle\Model\MessageInterface;
use Grcs\InternalMessagesBundle\Model\ReadableInterface;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Doctrine\ORM\Query\Builder;

/**
 * Default ORM MessageManager.
 */
class MessageManager extends BaseMessageManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EntityManager $em
     * @param $class
     */
    public function __construct(EntityManager $em, $class)
    {
        $this->em         = $em;
        $this->repository = $em->getRepository($class);
        $this->class      = $em->getClassMetadata($class)->name;
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::getUnreadMessageByParticipant()
     */
    public function getUnreadMessageByParticipant(ParticipantInterface $participant)
    {
        $builder = $this->repository->createQueryBuilder('m');

        return (int)$builder
            ->select($builder->expr()->count('m.id'))

            ->andWhere('m.recipient = :recipient')
            ->setParameter('recipient', $participant->getId())

            ->andWhere('m.isRead = :isRead')
            ->setParameter('isRead', false, \PDO::PARAM_BOOL)

            ->andWhere('m.isDeletedByRecipient = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::getTotalMessageByParticipant()
     */
    public function getTotalMessageByParticipant(ParticipantInterface $participant)
    {
        $builder = $this->repository->createQueryBuilder('m');

        return (int)$builder
            ->select($builder->expr()->count('m.id'))

            ->andWhere('m.recipient = :recipient')
            ->setParameter('recipient', $participant->getId())

            ->andWhere('m.isDeletedByRecipient = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::getTotalOutMessageByParticipant()
     */
    public function getTotalOutMessageByParticipant(ParticipantInterface $participant)
    {
        $builder = $this->repository->createQueryBuilder('m');

        return (int)$builder
            ->select($builder->expr()->count('m.id'))

            ->andWhere('m.sender = :sender')
            ->setParameter('sender', $participant->getId())

            ->andWhere('m.isDeletedBySender = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL)

            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::getMessagesByParticipant()
     */
    public function getMessagesByParticipant(ParticipantInterface $participant, $getQuery = false,
                                             $createdAtOrder = 'desc', $isReadOrder = 'asc')
    {
        $builder = $this->repository->createQueryBuilder('m');

        $query = $builder
            ->select()

            ->andWhere('m.recipient = :recipient')
            ->setParameter('recipient', $participant->getId())

            ->andWhere('m.isDeletedByRecipient = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL);

        if ($createdAtOrder) {
            $query = $query->addOrderBy('m.createdAt', $createdAtOrder);
        }

        if ($isReadOrder) {
            $query = $query->addOrderBy('m.isRead', $isReadOrder);
        }

        $query = $query->getQuery();

        return ($getQuery) ? $query : $query->getResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::getMessagesFromParticipant()
     */
    public function getMessagesFromParticipant(ParticipantInterface $participant, $getQuery = false,
                                               $createdAtOrder = 'desc')
    {
        $builder = $this->repository->createQueryBuilder('m');

        $query = $builder
            ->select()

            ->andWhere('m.sender = :sender')
            ->setParameter('sender', $participant->getId())

            ->andWhere('m.isDeletedBySender = :isDeleted')
            ->setParameter('isDeleted', false, \PDO::PARAM_BOOL);

        if ($createdAtOrder) {
            $query = $query->addOrderBy('m.createdAt', $createdAtOrder);
        }

        $query = $query->getQuery();

        return ($getQuery) ? $query : $query->getResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::findMessageById()
     */
    public function findMessageById($messageId)
    {
        return $this->repository->find($messageId);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::markAsRead()
     */
    public function markAsRead(ReadableInterface $readable)
    {
        $readable->setIsRead(true);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::markAsUnread()
     */
    public function markAsUnread(ReadableInterface $readable)
    {
        $readable->setIsRead(false);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::markAsDeletedBySender()
     */
    public function markAsDeletedBySender(DeletableInterface $deletable)
    {
        $deletable->setIsDeletedBySender(true);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::markAsDeletedByRecipient()
     */
    public function markAsDeletedByRecipient(DeletableInterface $deletable)
    {
        $deletable->setIsDeletedByRecipient(true);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::saveMessage()
     */
    public function saveMessage(MessageInterface $message, $andFlush = true)
    {
        $this->em->persist($message);
        if ($andFlush) {
            $this->em->flush();
        }
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\MessageManager::refresh()
     */
    public function refresh($object)
    {
        $this->em->refresh($object);
    }

    /**
     * Returns the fully qualified comment thread class name
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

}
