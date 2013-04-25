# php-lyte-xml

The base classes for XML work in php have a few little quirks that annoy me a lot, this is a simple collection of fixes for those [[annoyances.]]

## Examples

### Nested CDATA in XMLWriter

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

### Expanding to a DOMNode from XMLReader

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
