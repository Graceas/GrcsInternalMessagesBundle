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

use Grcs\InternalMessagesBundle\ModelManager\FilterManager as BaseFilterManager;
use Doctrine\ORM\EntityManager;

/**
 * Default ORM FilterManager.
 */
class FilterManager extends BaseFilterManager
{
    /**
     * @var bool
     */
    protected $enable;

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

        if (!$class) {
            $this->enable = false;
        } else {
            $this->enable = true;
            $this->repository = $em->getRepository($class);
            $this->class      = $em->getClassMetadata($class)->name;
        }
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\FilterManager::getFilters()
     */
    public function getFilters()
    {
        $builder = $this->repository->createQueryBuilder('f');

        $query = $builder
            ->select()

            ->andWhere('f.enabled = :enabled')
            ->setParameter('enabled', true, \PDO::PARAM_BOOL);

        $query = $query->getQuery();

        return $query->getResult();
    }

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\FilterManager::refresh()
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

    /**
     * @see Grcs\InternalMessagesBundle\ModelManager\FilterManager::refresh()
     */
    public function isEnabled()
    {
        return $this->enable;
    }

}
