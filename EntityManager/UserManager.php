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

use Grcs\InternalMessagesBundle\ModelManager\UserManager as BaseUserManager;
use Doctrine\ORM\EntityManager;
use Grcs\InternalMessagesBundle\Model\ParticipantInterface;
use Doctrine\ORM\Query\Builder;

/**
 * Default ORM UserManager.
 */
class UserManager extends BaseUserManager
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
     * @see Grcs\InternalMessagesBundle\ModelManager\UserManager::findUserById()
     */
    public function findUserById($userId)
    {
        return $this->repository->find($userId);
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\UserManager::refresh()
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
