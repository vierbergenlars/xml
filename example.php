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

require __DIR__ . '/vendor/autoload.php';

use vierbergenlars\Xml\XmlElement;
use vierbergenlars\Xml\XmlElementInterface;
use vierbergenlars\Xml\XmlCollectionInterface;
use vierbergenlars\Xml\XmlAttributesInterface;

$xml = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<result page="1" items_per_page="2" total="8">
  <entry id="30">
    <filename><![CDATA[Screenshot from 2013-10-21 19:31:42.png]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Exercise]]></type>
    <link rel="self" href="/files/30" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Exercise/Screenshot-from-2013-10-21-19:31:42.png" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="40">
    <filename><![CDATA[Screen Shot 2013-11-17 at 13.34.30.xcf]]></filename>
    <course id="1"><![CDATA[Analyse]]></course>
    <type id="1"><![CDATA[Slides]]></type>
    <link rel="self" href="/files/40" type="api"/>
    <link rel="self" href="http://bay.dev/file/Algebra/Slides/Screen-Shot-2013-11-17-at-13.34.30.xcf" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
</result>
XML;

$simpleXml  = new \SimpleXMLElement($xml);
$xmlElement = new XmlElement($simpleXml);

$totalPages = ceil($xmlElement->attr('total') / $xmlElement->attr('items_per_page'));
echo $xmlElement->attr('page') . '/' . $totalPages . "\n";

$files = $xmlElement->children();
/* @var $files XmlCollectionInterface */

foreach($files as $file) {
    /* @var $file XmlElementInterface */
    echo 'File ' . $file->attr('id') . ":\n";
    // echo '  Filename: ' . $file->child('filename')->text() . "\n";
    foreach($file->children() as $fileProperty) {
        /* @var $fileProperty XmlElementInterface */
        if($fileProperty->getName() != 'link')
            echo '  ' . ucfirst($fileProperty->getName()) . ': ' . $fileProperty->text() . "\n";
    }
    echo "  Links:\n";
    foreach($file->children('link') as $link) {
        /* @var $link XmlElementInterface */
        echo '    ' . $link->attr('href');
        foreach($link->attributes() as $attr => $value) {
            if($attr != 'href')
                echo ' (' . $attr . '=' . $value . ')';
        }
        echo "\n";
    }

    echo '  Weblink: ' . $file->children('link')->find(array('rel'  => 'self', 'type' => 'www'))->get(0)->attr('href') . "\n";
//    echo '  Weblink: ' . $file->child('link', array('rel'  => 'self', 'type' => 'www'))->attr('href') . "\n";
}

echo 'Total: ' . $files->count() . "\n";
