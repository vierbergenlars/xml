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

class XmlElement
    implements XmlElementInterface
{
    private $elem;

    public function __construct(SimpleXMLElement $elem)
    {
        $this->elem = $elem;
    }

    public function getName()
    {
        return $this->elem->getName();
    }

    public function _swapoutInternal(SimpleXMLElement $elem)
    {
        $this->elem = $elem;
    }

    public function text()
    {
        return (string) $this->elem;
    }

    public function setText($text)
    {
        $this->elem[0] = $text;
        return $this;
    }

    public function attr($name)
    {
        return $this->attributes()->get($name);
    }

    public function attributes()
    {
        return new XmlAttributes($this->elem);
    }

    public function addChild($name, $value = null)
    {
        if($name instanceof XmlElementInterface) {
            $value = $name;
            $name = $value->getName();
        }

        $coreElement = $this->elem->addChild($name);
        $element = new XmlElement($coreElement);
        if($value instanceof XmlElementInterface) {
            foreach($value->attributes() as $k => $v) {
                $element->attributes()->set($k, $v);
            }

            foreach($value->children() as $child) {
                $element->addChild($child);
            }

            if($value->text()) {
                $element->setText($value->text());
            }
            $value->_swapoutInternal($coreElement);
        } else {
            $element->setText($value);
        }
        return $element;
    }

    public function child($name = null, $filter = array(), $pos = 0)
    {
        return $this->children($name)->find($filter)->get($pos);
    }

    public function children($name = null)
    {
        if($name !== null) {
            return new XmlCollection($this->elem->{$name});
        }
        return new XmlCollection($this->elem->children());
    }

    public function find($query = array())
    {
        $findStr = '*';
        foreach($query as $key => $value) {
            $findStr.='[@' . $key . '="' . $value . '"]';
        }
        return new XmlArrayCollection($this->elem->xpath($findStr));
    }

    public function __toString()
    {
        $dom = new \DOMDocument();
        $dom->formatOutput = true;
        $dom->preserveWhiteSpace = false;

        $dom->loadXML($this->elem->asXML());

        return $dom->saveXml();
    }
}

