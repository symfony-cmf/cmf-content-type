<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Psi\Component\ContentType\Mapping;

use Psi\Component\ContentType\MappingInterface;

/**
 * Represents the abstract mapping for a complex content type.
 *
 * For example an Image object which needs to be mapped to a single
 * property in the content object:
 *
 *    {
 *        'path' => (string)
 *        'width' => (integer)
 *        '...' => (...)
 *    }
 *
 * Storage drivers will then be able to automatically map complex objects
 * belonging to the field types.
 */
class CompoundMapping implements \IteratorAggregate, MappingInterface
{
    private $classFqn;
    private $mappings;

    public function __construct($classFqn, array $mappings = [])
    {
        $this->mappings = $mappings;
        $this->classFqn = $classFqn;
    }

    public function getClass()
    {
        return $this->classFqn;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->mappings);
    }
}
