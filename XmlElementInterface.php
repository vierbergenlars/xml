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

/**
 * Interface for an xml element
 */
interface XmlElementInterface
{
    /**
     * Get the name of the element
     * @return string
     */
    public function getName();
    /**
     * Get the text contained in the element
     * @return string
     */
    public function text();
    /**
     * Gets the value of an attribute
     * @param string $name The name of the attribute to get
     * @return string
     */
    public function attr($name);
    /**
     * Gets all attributes of the element
     * @return XmlAttributesInterface
     */
    public function attributes();
    /**
     * Gets the child with the specified name at the specified position
     * @param string $name The name of the children to get
     * @param array $filter Filter the children of the element on their attributes
     * @param int $pos The position of the child to get from the collection
     * @return XmlElementInterface
     */
    public function child($name = null, $filter = array(), $pos = 0);
    /**
     * Gets all children with the specified name
     * @param string $name The name of the children to get
     * @return XmlCollectionInterface
     */
    public function children($name = null);
    /**
     * Filter the children of the element on their attributes
     * @param array $attributes
     * @return XmlCollectionInterface
     */
    public function find($attributes = array());
}

