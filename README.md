# php-lyte-xml

[![Build Status](https://travis-ci.org/neerolyte/php-lyte-xml.png)](https://travis-ci.org/neerolyte/php-lyte-xml)

The base classes for XML work in php have a few little quirks that annoy me a lot, this is a simple collection of fixes for those annoyances.

Some of what I'm trying to put in is going to be purely experimental, so you use at you're own risk :)

# Features

 * Correctly encodes nested CDATA
 * `XMLReader` expanded `DOMNode`s actually has an `ownerDocument`
 * Lazy OO xpaths `LyteDOMDocument` has a `xpath` propery that exists anywhere your doc does
 * `XPath` functions on `DOMNode`s that know their context
 * Key/Value pair iterator

# Examples

## Nested CDATA in XMLWriter

There's a [fairly well known](http://en.wikipedia.org/wiki/CDATA#Nesting) method for working around the fact that XML doesn't actually let you nest a CDATA tag inside another one, but XMLWriter doesn't bother to apply this fix for you. Which is a problem if for instance you're transporting HTML fragments within another XML format.

With `XMLWriter`:
```php
$writer = new XMLWriter();
$writer->openMemory();
$writer->writeCData('<![CDATA[a little bit of cdata]]>');
echo $writer->flush()."\n";
```
will result in:
```
<![CDATA[<![CDATA[a little bit of cdata]]>]]>
```
which isn't valid XML!

Use `LyteXMLWriter` instead and you'll get something that works the way you expect:
```php
$writer = new LyteXMLWriter();
$writer->openMemory();
$writer->writeCData('<![CDATA[a little bit of cdata]]>');
echo $writer->flush()."\n";
```

will result in:
```
<![CDATA[<![CDATA[a little bit of cdata]]]]><![CDATA[>]]>
```

## Expanding to a DOMNode from XMLReader

With the default XMLReader if you call `expand()` you get back a `DOMNode` which is nice, but it has its `ownerDocument` property set to `null`, which makes things like using a `DOMXPath` or saving it to an XML string snippet quite difficult.

E.g. with the normal `XMLReader`:
```php
$reader = new XMLReader();
$reader->xml('<foo>bar</foo>');
$reader->read();
$node = $reader->expand();
echo $node->ownerDocument->saveXML();
```

results in:
```
PHP Fatal error:  Call to a member function saveXML() on a non-object in - on line 6
```

... oops!

With `LyteXMLReader` if you expand a node it creates the `ownerDocument` for you though:
```php
$reader = new LyteXMLReader();
$reader->xml('<foo>bar</foo>');
$reader->read();
$node = $reader->expand();                                                                                             
echo $node->ownerDocument->saveXML();
```

works this time:
```
<?xml version="1.0"?>
<foo>bar</foo>
```

## Lazy XPaths

PHP has fairly relaibly XPath support in the form of `DOMXPath`, but it's not directly attached to anything, breaking your nice OO context.

`LyteDOMDocument` will lazily create a `DOMXPath` object for use if you just ask for it, e.g. with regular `DOMDocument`:
```php
$doc = new DOMDocument();
$doc->load('<foo/>');
$xpath = new DOMXPath($doc);
// and now I've got to pass around $doc and $xpath or recreate $xpath many times
```

with `LyteDOMDocument`:
```php
$doc = new LyteDOMDocument();
$doc->loadXML('<foo/>');
// now I can just use the xpath
$nodes = $doc->xpath->query('/foo');
```

## Contextified DOMNode XPath functions

Normally to run a XPath under a specific context you have to do a fair bit of set up, e.g.:
```php
$doc = new DOMDocument();
$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');
$xpath = new DOMXPath($doc);
$node = $doc->firstChild;
$nodes = $xpath->query('foo/text()', $node);
```

but `LyteDOMNode` provides XPath functions directly that are already contextified:
```php
$doc = new LyteDOMDocument();
$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');
$nodes = $doc->firstChild->xPathQuery('foo/text()');
```

There's also a `LyteDOMNode::xPathEvaluate()` function.

## Key/Value pair iterator

I seem to have to parse XML with key pairs a lot, e.g:
```xml
<root>
	<key1>value1</key1>
	<key2>value2</key2>
	...
	<key3>value3</key3>
</root>
```

With `LyteDOMNodeList` I've provided a `toPairs()` function to simplify this operation:
```php
// once you have a node with the key/pairs in it:
$node = ...;
// you can just iterate over it:
foreach ($node->childNodes as $k => $v) {
	...
}
```

# Caveats

Most of the classes I've created do not directly inherit from the XML ones, e.g. `new LyteDOMDocument() instanceof DOMDocument` is false. I've currently done this because to avoid duplicating memory all over the place and reserializing too much of the XML, I really need to use the decorator pattern, but even with PHP's [magic methods](http://php.net/manual/en/language.oop5.magic.php) I can't find a way to both inherit and decorate an object. I've even looked in to using the [Reflection API](http://php.net/manual/en/book.reflection.php) to walk the upstream classes and selectively `eval` a new class in to existence, but ran in to problems with many of public properties getting updated at odd times by the base DOM classes.

The net result is a bunch of objects that walk like ducks, talk like ducks, but you might have trouble in weird corner cases convincing PHP that they're ducks, but still send me any bugs when you run in to issues.

If anyone can solve this, lodge an issue :)
