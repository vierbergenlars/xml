XML
===

[![Build Status](https://secure.travis-ci.org/vierbergenlars/xml.png)](http://travis-ci.org/vierbergenlars/xml)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/vierbergenlars/xml/badges/quality-score.png?s=6ec9273ceef01190afa0e8e2284e904fb51da8ad)](https://scrutinizer-ci.com/g/vierbergenlars/xml/)
[![Latest Stable Version](https://poser.pugx.org/vierbergenlars/xml/v/stable.png)](https://packagist.org/packages/vierbergenlars/xml)

A sane wrapper around [SimpleXML](http://be2.php.net/manual/en/book.simplexml.php)

## Installation

`composer require vierbergenlars/xml:@stable`

> **Protip:** you should browse the
> [`vierbergenlars/xml`](https://packagist.org/packages/vierbergenlars/xml)
> page to choose a stable version to use, avoid the `@stable` meta constraint.

## Reference

### `XmlElementInterface`

| Return                   | Function signature                  | Documentation                                                                               |
|-------------------------:|:------------------------------------|---------------------------------------------------------------------------------------------|
| `string`                 | `getName()`                         | Get the element tag name                                                                    |
| `string`                 | `text()`                            | Get the text contained in the element                                                       |
| `string`                 | `attr(string $name)`                | Get the value of the attribute `$name` on the element                                       |
| `XmlAttributesInterface` | `attributes()`                      | Gets all attributes on the element                                                          |
| `XmlCollectionInterface` | `children(string $name = null)`     | Get all children of the element (or only those with the specified name if `$name !== null`) |
| `XmlCollectionInterface` | `find(array $attributes = array())` | Get the children of the element whose attributes match those in `$attributes`.              |

### `XmlCollectionInterface`: `Iterator`, `ArrayAccess`, `Countable`

| Return                   | Function signature                  | Documentation                                         |
|-------------------------:|:------------------------------------|-------------------------------------------------------|
| `XmlElementInterface`    | `get(int $pos)`                     | Get the element at the given position                 |
| `XmlCollectionInterface` | `find(array $attributes = array())` | Filters members of the collection on their attributes |

### `XmlAttributesInterface`: `Iterator`, `ArrayAccess`, `Countable`

| Return                   | Function signature                  | Documentation                          |
|-------------------------:|:------------------------------------|----------------------------------------|
| `string|null`            | `get(string $name)`                 | Get the value of the attribute `$name` |

## Usage

Only the `XmlElement` class should be instanciated in your code.

```php
use vierbergenlars\Xml\XmlElement;

$simpleXml = new \SimpleXMLElement($xml);
$xmlElement = new XmlElement($simpleXml);

```

The examples are based around following xml structure:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<result page="1" items_per_page="2" total="2">
  <entry id="30">
    <filename><![CDATA[Screenshot from 2013-10-21 19:31:42.png]]></filename>
    <course id="1"><![CDATA[Algebra]]></course>
    <type id="1"><![CDATA[Exercise]]></type>
    <link rel="self" href="/files/30" type="api"/>
    <link rel="self" href="http://example.invalid/file/Algebra/Exercise/Screenshot%20from%202013-10-21%2019:31:42.png" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
  <entry id="40">
    <filename><![CDATA[Screen Shot 2013-11-17 at 13.34.30.xcf]]></filename>
    <course id="1"><![CDATA[Analyse]]></course>
    <type id="1"><![CDATA[Slides]]></type>
    <link rel="self" href="/files/40" type="api"/>
    <link rel="self" href="http://example.invalid/file/Algebra/Slides/Screen%20Shot%202013-11-17%20at%2013.34.30.xcf" type="www"/>
    <link rel="author" href="/users/vierbergenlars"/>
  </entry>
</result>
```

> This example is a copy of [example.php](https://github.com/vierbergenlars/xml/blob/master/example.php),
> which is available in the repository for experimentation.

```php
$totalPages = ceil($xmlElement->attr('total') / $xmlElement->attr('items_per_page'));
echo $xmlElement->attr('page') . '/' . $totalPages . "\n";
```

`XmlElement::attr($name)` gets the value of the XML attribute `$name` on the current element.

```php
$files = $xmlElement->children();
/* @var $files XmlCollectionInterface */
```

`XmlElement::children()` gets the immediate children of the current element.
The result implements `XmlCollectionInterface`. It is an `Iterator`, implements `ArrayAccess` and `Countable`.

For easier chaining in PHP 5.3, a `$XmlCollectionInterface->get($idx)` function is provided,
 which has the same effect as `$XmlCollectionInterface[$idx]`.

A `->find($attrs = array())` function is provided that returns a new `XmlCollectionInterface`,
 which only contains elements with the attributes passed to the function.

```php
foreach($files as $file) {
    /* @var $file XmlElementInterface */
    echo 'File ' . $file->attr('id') . ":\n";
    echo '  Filename: ' . $file->children('filename')->get(0)->text() . "\n";
```

Here, the children of `$file` are filtered to contain only elements with tagname `<filename>`.
Next, the first (and only) element is retrieved with `->get(0)`, and the text between its tags is returned (with `->text()`).`

```php
    echo "  Links:\n";
    foreach($file->children('link') as $link) {
```

Only the `<link>` elements are selected for iteration.

```php
        /* @var $link XmlElementInterface */
        echo '    ' . $link->attr('href');
        foreach($link->attributes() as $attr => $value) {
```

All attributes from the `<link>` element are selected.
`$link->attributes()` returns an implementation of `XmlAttributesInterface`.
The object acts as a read-only `array` with attribute names as keys and attribute values as value.
Additionally, it has a `->get($idx)` function for easier chaining.

```php
            if($attr != 'href')
                echo ' (' . $attr . '=' . $value . ')';
        }
        echo "\n";
    }

    echo '  Weblink: ' . $file->children('link')->find(array('rel'  => 'self', 'type' => 'www'))->get(0)->attr('href') . "\n";
```

This is an example of using `XmlCollectionInterface::find()` to only select the one `<link>` element with the desired attributes.

```php
}

echo 'Total: ' . $files->count() . "\n";
```

## License

[MIT](https://github.com/vierbergenlars/xml/blob/master/LICENSE.md)
