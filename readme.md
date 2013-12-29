XML
===

[![Build Status](https://secure.travis-ci.org/vierbergenlars/xml.png)](http://travis-ci.org/vierbergenlars/xml)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/vierbergenlars/xml/badges/quality-score.png?s=6ec9273ceef01190afa0e8e2284e904fb51da8ad)](https://scrutinizer-ci.com/g/vierbergenlars/xml/)
[![Latest Stable Version](https://poser.pugx.org/vierbergenlars/xml/v/stable.png)](https://packagist.org/packages/vierbergenlars/xml)

A sane wrapper around [SimpleXML](http://be2.php.net/manual/en/book.simplexml.php)


* [Installation](#installation)
* [Usage](#usage)
* [Reference](#reference)
  - [`XmlElementInterface`](#xmlelementinterface)
  - [`XmlCollectionInterface`](#xmlcollectioninterface)
  - [`XmlAttributesInterface`](#xmlattributesinterface)
* [License](#license)


## Installation

`composer require vierbergenlars/xml:@stable`

> **Protip:** you should browse the
> [`vierbergenlars/xml`](https://packagist.org/packages/vierbergenlars/xml)
> page to choose a stable version to use, avoid the `@stable` meta constraint.


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

### `XmlCollectionInterface`

Implements interfaces `Iterator`, `ArrayAccess`, `Countable`

| Return                   | Function signature                  | Documentation                                                     |
|-------------------------:|:------------------------------------|-------------------------------------------------------------------|
| `XmlElementInterface`    | `get(int $pos)`                     | Get the element at the given position                             |
| `XmlCollectionInterface` | `find(array $attributes = array())` | Filters members of the collection on their attributes             |
| `int`                    | `count()`                           | `Countable::count()` Count the elements in the collection         |
| `boolean`                | `offsetExists(int $offset)`         | `ArrayAccess::offsetExists` Whether a offset exists (see `get()`) |
| `XmlElementInterface`    | `offsetGet(int $offset)`            | `ArrayAccess::offsetGet()` Offset to retrieve                     |
| -                        | `offsetSet()`                       | Throws `LogicException`. The collection is read-only.             |
| -                        | `offsetUnset()`                     | Throws `LogicException`. The collection is read-only.             |
| `XmlElementInterface`    | `current()`                         | `Iterator::current()` Return the current element                  |
| `int`                    | `key()`                             | `Iterator::key()` Return key of the current element               |
| `void`                   | `next()`                            | `Iterator::next()` Move forward to next element                   |
| `void`                   | `rewind()`                          | `Iterator::rewind()` Rewind the iterator to the first element     |
| `boolean`                | `valid()`                           | `Iterator::valid()` Checks if current position is valid           |

1. This object implements `ArrayAccess`:
  * `$xmlCollectionInterface[$offset]` calls `$xmlCollectionInterface->offsetGet($offset)`
  * `isset($xmlCollectionInterface[$offset])` calls `$xmlCollectionInterface->offsetExists($offset)`
  * `$xmlCollectionInterface[$offset] = $mixed` calls `$xmlCollectionInterface->offsetSet($offset, $mixed)` and throws an exception.
  * `unset($xmlCollectionInterface[$offset])` calls `$xmlCollectionInterface->offsetUnset($offset)` and throws an exception.

2. This object implements `Countable`:
  * `count($xmlCollectionInterface)` calls `$xmlCollectionInterface->count()`

3. This object implements `Iterator`
  * `foreach($xmlCollectionInterface as $key => $value){}` calls `$xmlCollectionInterface->rewind()` once, `$xmlCollectionInterface->valid()` before each iteration, and if it returns true sets `$key = $xmlCollectionInterface->key(); $value = $xmlCollectionInterface->current()`. Finally calls `$xmlCollectionInterface->next()`.

### `XmlAttributesInterface`

Implements interfaces `Iterator`, `ArrayAccess`, `Countable`

| Return                   | Function signature                  | Documentation                                                      |
|-------------------------:|:------------------------------------|--------------------------------------------------------------------|
| `string` or `null`       | `get(string $name)`                 | Get the value of the attribute `$name`                             |
| `int`                    | `count()`                           | `Countable::count()` Count the elements in the collection          |
| `boolean`                | `offsetExists(int $offset)`         | `ArrayAccess::offsetExists` Whether a offset exists  (see `get()`) |
| `string` or `null`       | `offsetGet(int $offset)`            | `ArrayAccess::offsetGet()` Offset to retrieve                      |
| -                        | `offsetSet()`                       | Throws `LogicException`. The collection is read-only.              |
| -                        | `offsetUnset()`                     | Throws `LogicException`. The collection is read-only.              |
| `string`                 | `current()`                         | `Iterator::current()` Return the current element                   |
| `string`                 | `key()`                             | `Iterator::key()` Return key of the current element                |
| `void`                   | `next()`                            | `Iterator::next()` Move forward to next element                    |
| `void`                   | `rewind()`                          | `Iterator::rewind()` Rewind the iterator to the first element      |
| `boolean`                | `valid()`                           | `Iterator::valid()` Checks if current position is valid            |

1. This object implements `ArrayAccess`:
     * `$xmlAttributesInterface[$offset]` calls `$xmlAttributesInterface->offsetGet($offset)`
     * `isset($xmlAttributesInterface[$offset])` calls `$xmlAttributesInterface->offsetExists($offset)`
     * `$xmlAttributesInterface[$offset] = $mixed` calls `$xmlAttributesInterface->offsetSet($offset, $mixed)` and throws an exception.
     * `unset($xmlAttributesInterface[$offset])` calls `$xmlAttributesInterface->offsetUnset($offset)` and throws an exception.

2. This object implements `Countable`:
     * `count($xmlAttributesInterface)` calls `$xmlAttributesInterface->count()`

3. This object implements `Iterator`
     * `foreach($xmlAttributesInterface as $key => $value){}` calls `$xmlAttributesInterface->rewind()` once, `$xmlAttributesInterface->valid()` before each iteration, and if it returns true sets `$key = $xmlAttributesInterface->key(); $value = $xmlAttributesInterface->current()`. Finally calls `$xmlAttributesInterface->next()`.


## License

[MIT](https://github.com/vierbergenlars/xml/blob/master/LICENSE.md)
