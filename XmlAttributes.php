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

class XmlAttributes
    implements XmlAttributesInterface
{
    private $element;
    private $array = array();

    public function __construct(SimpleXMLElement $elem)
    {
        $this->element = $elem;
        $elem = (array) $elem;
        if(isset($elem['@attributes'])) {
            $this->array = (array) $elem['@attributes'];
        }
    }

    public function get($name)
    {
        return $this[$name];
    }

    public function set($name, $value)
    {
        $this[$name] = $value;
    }

    public function current()
    {
        return current($this->array);
    }

    public function key()
    {
        return key($this->array);
    }

    public function next()
    {
        next($this->array);
    }

    public function rewind()
    {
        reset($this->array);
    }

    public function valid()
    {
        return key($this->array) !== null;
    }

    public function offsetSet($index, $newval)
    {
        if($this->offsetExists($index)) {
            $this->element[$index] = $newval;
        } else {
            $this->element->addAttribute($index, $newval);
        }
        $this->array[$index] = $newval;
    }

    public function offsetUnset($index)
    {
        unset($this->element[$index]);
        unset($this->array[$index]);
    }

    public function count()
    {
        return count($this->array);
    }

    public function offsetExists($offset)
    {
        return isset($this->array[$offset]);
    }

    public function offsetGet($offset)
    {
        if(!isset($this->array[$offset]))
            return null;
        return $this->array[$offset];
    }

    public function __toString()
    {
        return $this->element->asXML();
    }
}

