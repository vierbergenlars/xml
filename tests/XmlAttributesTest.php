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

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../vendor/simpletest/simpletest/autorun.php';

use vierbergenlars\Xml\XmlElement;
use \SimpleXMLElement;
use vierbergenlars\Xml\XmlAttributesInterface;
use vierbergenlars\Xml\XmlCollectionInterface;
use vierbergenlars\Xml\XmlElementInterface;

class XmlAttributesTest
    extends UnitTestCase
{
    private function getXmlElement()
    {
        $xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<result page="1" items_per_page="8" total="9">
    <filename><![CDATA[Screenshot from 2013-10-21 19:31:42.png]]></filename>
</result>
XML;
        return new XmlElement(new SimpleXMLElement($xml));
    }

    public function testGet()
    {
        $this->assertEqual($this->getXmlElement()->attributes()->get('page'), 1);
    }

    public function testCount()
    {
        $this->assertEqual($this->getXmlElement()->attributes()->count(), 3);
    }

    public function testIteration()
    {
        $attributes = $this->getXmlElement()->attributes();

        $this->assertTrue($attributes->valid());
        $this->assertEqual($attributes->current(), 1);
        $this->assertEqual($attributes->key(), 'page');
        $attributes->next();

        $this->assertTrue($attributes->valid());
        $this->assertEqual($attributes->current(), 8);
        $this->assertEqual($attributes->key(), 'items_per_page');
        $attributes->next();

        $this->assertTrue($attributes->valid());
        $this->assertEqual($attributes->current(), 9);
        $this->assertEqual($attributes->key(), 'total');
        $attributes->next();

        $this->assertFalse($attributes->valid());
        $attributes->rewind();
        $this->assertTrue($attributes->valid());
        $this->assertEqual($attributes->current(), 1);
        $this->assertEqual($attributes->key(), 'page');
    }

    public function testArrayAccess()
    {
        $attributes = $this->getXmlElement()->attributes();

        $this->assertNotNull($attributes['page']);
        $this->assertTrue($attributes->offsetExists('page'));
        $this->assertNull($attributes['xx']);
        $this->assertFalse($attributes->offsetExists('xx'));
        $this->assertTrue($attributes['items_per_page'], 8);
    }

    public function testArrayAccessSet()
    {
        $this->expectException();
        $attributes         = $this->getXmlElement()->attributes();
        $attributes['page'] = 1;
    }

    public function testArrayAccessUnset()
    {
        $el = $this->getXmlElement();
        $attributes = $el->attributes();
        unset($attributes['total']);
        $this->assertEqual(str_replace(array("\n","\t", '  '), '',$el->__toString()),
'<?xml version="1.0" encoding="UTF-8"?>'.
'<result page="1" items_per_page="8">'.
    '<filename><![CDATA[Screenshot from 2013-10-21 19:31:42.png]]></filename>'.
'</result>');

    }

    public function testNoAttributesElement()
    {
        $empty = $this->getXmlElement()->child('filename')->attributes();
        $this->assertEqual($empty->count(), 0);
        $this->assertFalse($empty->valid());
    }

}
