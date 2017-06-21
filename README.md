# php-lyte-xml

[![Build Status](https://api.travis-ci.org/neerolyte/php-lyte-xml.svg?branch=master)](https://travis-ci.org/neerolyte/php-lyte-xml)
[![Coverage Status](https://coveralls.io/repos/github/neerolyte/php-lyte-xml/badge.svg?branch=master)](https://coveralls.io/github/neerolyte/php-lyte-xml?branch=master)
[![Code Climate](https://codeclimate.com/github/neerolyte/php-lyte-xml/badges/gpa.svg)](https://codeclimate.com/github/neerolyte/php-lyte-xml)
[![Issue Count](https://codeclimate.com/github/neerolyte/php-lyte-xml/badges/issue_count.svg)](https://codeclimate.com/github/neerolyte/php-lyte-xml)

The base classes for XML work in php have a few little quirks that annoy me a lot, this is a simple collection of fixes for those annoyances.

Some of what I'm trying to put in is going to be purely experimental, so use at you're own risk :)

# Examples

 * [Nested CDATA in XMLWriter](#nested-cdata-in-xmlwriter)
 * [XMLReader expanded DOMNodes actually have ownerDocuments](#expanding-to-a-domnode-from-xmlreader)
 * [Lazy XPaths - XPaths that actually work in a OO manner](#lazy-xpaths)
 * [Contextified DOMNode XPath functions](#contextified-domnode-xpath-functions)
 * [Key/Value pair iterator](#keyvalue-pair-iterator)
 * [saveXML() anywhere](#savexml-anywhere)
 * [Translate to UTF8 on the fly](#translate-to-utf8-on-the-fly)

## Nested CDATA in XMLWriter

There's a [fairly well known](http://en.wikipedia.org/wiki/CDATA#Nesting) method for working around the fact that XML doesn't actually let you nest a CDATA tag inside another one, but XMLWriter doesn't bother to apply this fix for you. Which is a problem if for instance you're transporting HTML fragments within another XML format.

With `XMLWriter`:
```php
$writer = new \XMLWriter();
$writer->openMemory();
$writer->writeCData('<![CDATA[a little bit of cdata]]>');
echo $writer->flush()."\n";
```
will result in:
```
<![CDATA[<![CDATA[a little bit of cdata]]>]]>
```
which isn't valid XML!

Use `Lyte\XML\XMLWriter` instead and you'll get something that works the way you expect:
```php
use Lyte\XML\XMLWriter;
$writer = new XMLWriter();
$writer->openMemory();
$writer->writeCData('<![CDATA[a little bit of cdata]]>');
echo $writer->flush()."\n";
```

will result in:
```
<![CDATA[<![CDATA[a little bit of cdata]]]]><![CDATA[>]]>
```

## Expanding to a DOMNode from XMLReader

With the native `XMLReader` if you call `expand()` you get back a `DOMNode`, which has its `ownerDocument` property set to `null`, which makes things like using a `DOMXPath` or saving it to an XML string snippet quite difficult.

E.g. with the native `XMLReader`:

```php
$reader = new \XMLReader();
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

With `Lyte\XML\XMLReader` if you expand a node it creates the `ownerDocument` for you:

```php
use Lyte\XML\XMLReader;
$reader = new XMLReader();
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

PHP has fairly relaible `XPath` support in the form of `DOMXPath`, but it's not directly attached to anything, breaking your nice OO context because now you either need to pass around two objects or continually reinstatiate your `DOMXPath` object.

`Lyte\XML\DOMDocument` will lazily create a `DOMXPath` object for use if you just ask for it, e.g. with the native `DOMDocument`:

```php
$doc = new \DOMDocument();
$doc->load('<foo/>');
$xpath = new \DOMXPath($doc);
// and now I've got to pass around $doc and $xpath or recreate $xpath many times
```

with `Lyte\XML\DOMDocument`:
```php
use Lyte\XML\DOMDocument;
$doc = new DOMDocument();
$doc->loadXML('<foo/>');
// now I can just use the xpath (the xpath property gets instantiated to a Lyte\XML\DOMXPath as it's requested)
$nodes = $doc->xpath->query('/foo');
```

## Contextified DOMNode XPath functions

Normally to run a XPath in a specific context you have to do a fair bit of set up, e.g.:

```php
$doc = new \DOMDocument();
$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');
$xpath = new \DOMXPath($doc);
$node = $doc->firstChild;
$nodes = $xpath->query('foo/text()', $node);
```

but `Lyte\XML\DOMNode` provides XPath functions directly that are already contextified:

```php
use Lyte\XML\DOMDocument;
$doc = new DOMDocument();
$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');
$nodes = $doc->firstChild->xPathQuery('foo/text()');
```

There's also a `Lyte\XML\DOMNode::xPathEvaluate()` function that's synonymous with `DOMXPath::evaluate()` with the context already filled out.

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

With `Lyte\XML\DOMNodeList` I've provided a `toPairs()` function to simplify this operation:

```php
// once you have a node with the key/pairs in it:
$node = ...;
// you can just iterate over it:
foreach ($node->childNodes->toPairs() as $k => $v) {
	...
}
```

## saveXML() anywhere

There's a commonish trick to get the XML just of a subtree of an XML DOM using `ownerDocument` like so:

```php
$xml = $node->ownerDocument->saveXML($node);
```

with a `Lyte\XML\DOMNode` you can just ask it to save the XML directly:

```php
$xml = $node->saveXML();
```

# Translate to UTF8 on the fly

## Lyte\XML\XMLWriter

Simply state what the source encoding is and have it transcoded on the fly, e.g:

```php
use Lyte\XML\XMLWriter;
$writer = new Lyte\XML\XMLWriter();
$writer->openMemory();
$writer->setSourceCharacterEncoding('Windows-1252');
$writer->text("Don\x92t you hate word quotes?\n");
echo $writer->flush();
```

Produces:

```
Don’t you hate word quotes?
```

## Lyte\XML\DOMDocument::loadHTML()

Load HTML from an arbitrary character set:

```
use Lyte\XML\DOMDocument;
$dom = new DOMDocument();
$html = "<p>\x93bendy quotes\x94</p>";
$encoding = 'Windows-1252';
$dom->loadHTML($html, $encoding);
```

# Caveats

Most of the classes I've created do not directly inherit from the XML ones, e.g. `new Lyte\XML\DOMDocument() instanceof \DOMDocument` is false. I've currently done this because to avoid duplicating memory all over the place and reserializing too much of the XML, I really need to use the decorator pattern, but even with PHP's [magic methods](http://php.net/manual/en/language.oop5.magic.php) I can't find a way to both inherit and decorate an object. I've even looked in to using the [Reflection API](http://php.net/manual/en/book.reflection.php) to walk the upstream classes and selectively `eval` a new class in to existence, but ran in to problems with many of public properties getting updated at odd times by the base DOM classes.

The net result is a bunch of objects that walk like ducks, talk like ducks, but you might have trouble in weird corner cases convincing PHP that they're ducks, but still send me any bugs when you run in to issues.

If anyone can solve this, lodge an issue :)
