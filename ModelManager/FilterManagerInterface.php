<?php

/*
 * This file is part of the Grcs package.
 *
 * (c) Alexander Gorelov <grac.ga@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Grcs\InternalMessagesBundle\ModelManager;


/**
 * Interface to be implemented by filter managers. This adds an additional level
 * of abstraction between your application, and the actual repository.
 *
 * All changes to user should happen through this interface.
 */
interface FilterManagerInterface
{
    /**
     * Refresh object
     *
     * @param $object
     * @return mixed
     */
    function refresh($object);

    /**
     * Get all enabled filters
     *
     * @return array filters
     */
    function getFilters();

    /**
     * Returns the filter's fully qualified class FilterManagerInterface.
     *
     * @return string
     */
    function getClass();

    /**
     * Get is enabled filters
     *
     * @return bool
     */
    function isEnabled();
}
