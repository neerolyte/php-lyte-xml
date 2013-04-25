# php-lyte-xml

The base classes for XML work in php have a few little quirks that annoy me a lot, this is a simple collection of fixes for those [[annoyances.]]

## Examples

### Nested CDATA

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

