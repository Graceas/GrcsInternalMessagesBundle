<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Your\EntityNamespace\Entity;

use Doctrine\ORM\Mapping as ORM;
use Grcs\InternalMessagesBundle\Entity\Filter as BaseFilter;

/**
 * YourFilter
 *
 * @ORM\Table(name="your_filters")
 * @ORM\Entity
 */
class YourFilter extends BaseFilter {

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::pattern
     *
     * @ORM\Column(name="pattern", type="text", length=255, nullable=false)
     */
    protected $pattern;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::replacement
     *
     * @ORM\Column(name="replacement", type="text", length=255, nullable=false)
     */
    protected $replacement;

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::enabled
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    protected $enabled;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::getPattern()
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::setPattern()
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::getReplacement()
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::setReplacement()
     */
    public function setReplacement($replacement)
    {
        $this->replacement = $replacement;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::getEnabled()
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Entity\Filter::setEnabled()
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

}