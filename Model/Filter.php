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


abstract class Filter implements FilterInterface
{
    /**
     * Unique id of the message
     *
     * @var mixed
     */
    protected $id;

    /**
     * Is enabled filter
     * @var bool
     */
    protected $enabled;

    /**
     * Replace regex pattern
     * @var string
     */
    protected $pattern;

    /**
     * Replace text
     * @var string
     */
    protected $replacement;

    /**
     * @see Grcs\InternalMessagesBundle\Model\FilterInterface::filter()
     */
    public function filter($text)
    {
        return preg_replace($this->pattern, $this->replacement, $text);
    }
}