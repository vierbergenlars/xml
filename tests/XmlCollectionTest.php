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

class XmlCollectionTest
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

    private function getXmlCollection()
    {
        return $this->getXmlElement()->children();
    }

    public function testCount()
    {
        $this->assertEqual($this->getXmlCollection()->count(), 8);
    }

    public function testIteration()
    {
        $collection = $this->getXmlCollection();

        $this->assertTrue($collection->valid());
        $this->assertTrue($collection->current() instanceof XmlElementInterface);
        $this->assertEqual($collection->current()->attr('id'), 30);
        $this->assertEqual($collection->key(), 0);
        $collection->next();

        $this->assertTrue($collection->valid());
        $this->assertTrue($collection->current() instanceof XmlElementInterface);
        $this->assertEqual($collection->current()->attr('id'), 35);
        $this->assertEqual($collection->key(), 1);

        for($i = 2; $i < 8; $i++)
            $collection->next();
        $this->assertTrue($collection->valid());
        $this->assertTrue($collection->current() instanceof XmlElementInterface);
        $this->assertEqual($collection->current()->attr('id'), 69);
        $this->assertEqual($collection->key(), 7);
        $collection->next();

        $this->assertFalse($collection->valid());
        $collection->rewind();
        $this->assertTrue($collection->valid());
        $this->assertTrue($collection->current() instanceof XmlElementInterface);
        $this->assertEqual($collection->current()->attr('id'), 30);
        $this->assertEqual($collection->key(), 0);
    }

    public function testArrayAccess()
    {
        $collection = $this->getXmlCollection();

        $this->assertNotNull($collection[0]);
        $this->assertTrue($collection->offsetExists(0));
        $this->assertNull($collection[8]);
        $this->assertFalse($collection->offsetExists(8));
        $this->assertTrue($collection[0] instanceof XmlElementInterface);
        $this->assertTrue($collection[0]->attr('id'), 30);
    }

    public function testArrayAccessSet()
    {
        $this->expectException();
        $collection    = $this->getXmlCollection();
        $collection[0] = 1;
    }

    public function testArrayAccessUnset()
    {
        $collection = $this->getXmlCollection();
        unset($collection[3]);
        $this->assertFalse(strpos($collection->__toString(), '<entry id="37">'));
    }

    public function testGet()
    {
        $this->assertTrue($this->getXmlCollection()->get(0) instanceof XmlElementInterface);
    }

    public function testFind()
    {
        $collection = $this->getXmlCollection()->get(0)->children('link');

        $this->assertTrue($collection->find() instanceof XmlCollectionInterface);
        $this->assertEqual($collection->find()->count(), 3);
        $this->assertEqual($collection->find(array('rel' => 'self'))->count(), 2);
        $this->assertEqual($collection->find(array('rel'  => 'self', 'type' => 'api'))->count(), 1);
        $this->assertEqual($collection->find(array('rel' => 'self'))->find(array(
                'type' => 'api'))->count(), 1);
        $this->assertEqual($collection->find(array('nx' => 1))->count(), 0);
    }

    public function testAddName()
    {
        $collection = $this->getXmlCollection();
        $el = $collection->add('xx');
        $this->assertTrue($el instanceof XmlElementInterface);
        $this->assertEqual($el->getName(), 'xx');
        $el->setText('yy');
        $el->attributes()->set('id', 5);

        $this->assertTrue(strpos($collection->__toString(), '<xx id="5">yy</xx>'));
    }

    public function testAddNameString()
    {
        $collection = $this->getXmlCollection();
        $el = $collection->add('xx', 'yy');
        $this->assertTrue($el instanceof XmlElementInterface);
        $this->assertEqual($el->getName(), 'xx');
        $this->assertEqual($el->text(), 'yy');
        $el->attributes()->set('id', 5);

        $this->assertTrue(strpos($collection->__toString(), '<xx id="5">yy</xx>'));
    }

    public function testAddXmlElement()
    {
        $el1 = new XmlElement(new SimpleXMLElement('<data p="d" va="re"><piece n="0">a</piece><piece n="1">b</piece></data>'));
        $el1Str = $el1->__toString();
        $collection = $this->getXmlCollection();
        $el = $collection->add($el1);
        $this->assertSame($el1Str, '<'.'?xml version="1.0"?>'."\n".$el->__toString()."\n");
        $added = $collection->find(array('p'=>'d', 'va'=>'re'));
        $this->assertEqual(1, count($added));
    }

    public function testAddXmlElementName()
    {
        $el1 = new XmlElement(new SimpleXMLElement('<data p="d" va="re"><piece n="0">a</piece><piece n="1">b</piece></data>'));
        $el1Str = $el1->__toString();
        $collection = $this->getXmlCollection();
        $el = $collection->add('xx', $el1);
        $this->assertSame(str_replace('data', 'xx', $el1Str), '<'.'?xml version="1.0"?>'."\n".$el->__toString()."\n");
        $added = $collection->find(array('p'=>'d', 'va'=>'re'));
        $this->assertEqual(1, count($added));
    }
}
