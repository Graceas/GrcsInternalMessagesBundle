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


interface FilterInterface
{
    /**
     * @param $text
     * @return string
     */
    function filter($text);

    /**
     * @return bool
     */
    function getEnabled();
}