<?php

/*
 * Copyright (c) 2013 Lars Vierbergen <vierbergenlars@gmail.com>
 * MIT licensed
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace vierbergenlars\Xml;

use \ArrayAccess;
use \Iterator;
use \Countable;

/**
 * Interface for a collection of xml elements
 */
interface XmlCollectionInterface extends ArrayAccess, Iterator, Countable
{
    /**
     * Get the element at the given position
     * @param int $pos
     * @return XmlElementInterface
     */
    public function get($pos);

    /**
     * Adds a new element to the collection
     * @param XmlElementInterface|string $name Name of the new element, or a description of the element
     * @param XmlElementInterface|string|null $value Copies all properties from the given element, or sets its string value (only if $name is a string)
     * @return XmlElementInterface The added element
     * @see XmlElementInterface::addChild()
     */
    public function add($name, $value);

    /**
     * Filters members of the collection on their attributes
     * @param array $attributes
     * @return XmlCollectionInterface
     */
    public function find($attributes = array());
}

