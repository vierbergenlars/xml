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
  <entry id="30">
    <filename><![CDATA[Screenshot from 2013-10-21 19:31:42.png]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/30" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/Screenshot%20from%202013-10-21%2019:31:42.png" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="35">
    <filename><![CDATA[Giraffe-wild-animals-2614055-1024-818.jpg]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/35" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/Giraffe-wild-animals-2614055-1024-818.jpg" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="36">
    <filename><![CDATA[rabid-giraffe1.jpg]]></filename>
    <course id="3"><![CDATA[Dynamica van Puntmassas]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/36" type="api"/>
    <link rel="self" href="http://bay.dev/file/Dynamica%20van%20Puntmassas/Oefening/rabid-giraffe1.jpg" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="37">
    <filename><![CDATA[logo.png]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="2"><![CDATA[Oplossing]]></type>
    <link rel="self" href="/files/37" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oplossing/logo.png" type="www"/>
    <link rel="author" href="/users/user"/>
  </entry>
  <entry id="40">
    <filename><![CDATA[Screen Shot 2013-11-17 at 13.34.30.xcf]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/40" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/Screen%20Shot%202013-11-17%20at%2013.34.30.xcf" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="45">
    <filename><![CDATA[passwd]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/45" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/passwd" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="68">
    <filename><![CDATA[composer.lock]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/68" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/composer.lock" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="69">
    <filename><![CDATA[composer.json]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Oefening]]></type>
    <link rel="self" href="/files/69" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Oefening/composer.json" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
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
        $this->expectException();
        $attributes = $this->getXmlElement()->attributes();
        unset($attributes['total']);
    }

    public function testNoAttributesElement()
    {
        $empty = $this->getXmlElement()->child()->child('filename')->attributes();
        $this->assertEqual($empty->count(), 0);
        $this->assertFalse($empty->valid());
    }

}
