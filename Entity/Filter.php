<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\Entity;

use Grcs\InternalMessagesBundle\Model\Filter as FilterMessage;
use Grcs\InternalMessagesBundle\Model\FilterInterface;

abstract class Filter extends FilterMessage
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::getPattern()
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::setPattern()
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::getReplacement()
     */
    public function getReplacement()
    {
        return $this->replacement;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::setReplacement()
     */
    public function setReplacement($replacement)
    {
        $this->replacement = $replacement;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::getEnabled()
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::setEnabled()
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

}
