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

use \SimpleXMLElement;

class XmlCollection
    implements XmlCollectionInterface
{
    private $elem;

    private $key = 0;

    public function __construct(SimpleXMLElement $elem)
    {
        $this->elem = $elem;
    }

    public function offsetExists($offset)
    {
        return isset($this->elem[$offset]);
    }

    public function offsetGet($offset)
    {
        if(!isset($this->elem[$offset]))
            return null;
        return new XmlElement($this->elem[$offset]);
    }

    public function offsetSet($offset, $value)
    {
        throw new \LogicException;
    }

    public function offsetUnset($offset)
    {
        throw new \LogicException;
    }

    public function current()
    {
        return $this[$this->key];
    }

    public function key()
    {
        return $this->key;
    }

    public function next()
    {
        $this->key++;
    }

    public function rewind()
    {
        $this->key = 0;
    }

    public function valid()
    {
        return $this->key < $this->count();
    }

    public function count()
    {
        return $this->elem->count();
    }

    public function get($id)
    {
        return $this[$id];
    }

    public function find($query = array())
    {
        if($query == array())
            return $this;
        $foundElements = array();
        foreach($this as $element) {
            /* @var $element XmlElement */
            $attributes = $element->attributes();
            $failed     = false;
            foreach($query as $attr => $value) {
                if($attributes->get($attr) != $value)
                    $failed = true;
            }
            if(!$failed)
                $foundElements[] = $element;
        }
        return new XmlArrayCollection($foundElements);
    }

}

