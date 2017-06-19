<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMDocument;
use Lyte\XML\DOMNode;
class DOMDocumentTest extends TestCase {
	public function testLyteDOMDocumentBehavesLikeDOMDocument() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo>bar</foo>');

		$this->assertEquals('foo', $doc->firstChild->nodeName);
		$this->assertEquals('bar', $doc->firstChild->firstChild->wholeText);
	}

	public function testFirstChildIsLyteDOMNode() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $doc->firstChild);
	}

	public function testHasXPathProperty() {
		$doc = new DOMDocument();

		$xpath = $doc->xpath;
		$this->assertInstanceOf('Lyte\\XML\\DOMXPath', $xpath);

		// ensure we get the same instance each time
		$this->assertTrue($xpath === $doc->xpath);
	}

	public function testHasXPathWorksOnOurDoc() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');

		$nodeName = $doc->xpath->query('/foo')->item(0)->nodeName;
		$this->assertEquals('foo', $nodeName);
	}

	public function testCanAppendEitherChildtype() {
		$doc = new DOMDocument();

		$node = $doc->getDecorated()->createElement('foo');
		$lnode = new DOMNode($doc->createElement('bar'));

		$doc->appendChild($node);
		$doc->appendChild($lnode);

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<bar/>', $doc->saveXML($lnode));
	}

	public function testRemoveEitherChildtype() {
		$doc = new DOMDocument();
		$doc->loadXML('<root><foo/><bar/></root>');

		$parent = $doc->getElementsByTagName('root')->item(0);

		$lnode = $doc->getElementsByTagName('foo')->item(0);
		$this->assertInstanceOf('Lyte\\XML\\DOMElement', $lnode);
		$node = $doc->getDecorated()->getElementsByTagName('bar')->item(0);
		$this->assertInstanceOf('DOMElement', $node);

		$parent->removeChild($lnode);
		$parent->removeChild($node);
	}

	public function testCanSaveEitherNodeTypeAsXML() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$lnode = new DOMNode($doc->firstChild);
		$node = $lnode->getDecorated();

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<foo/>', $doc->saveXML($lnode));
	}

	public function testLoadHTMLUTF8() {
		$doc = new DOMDocument();
		$str = '你好世界';
		$doc->loadHTML($str);
		$this->assertEquals($str, $doc->xpath->evaluate("string(//p/text())"));
	}

	public function testLoadHTMLISO_8859_1() {
		$doc = new DOMDocument();
		// This is actually a Windows-1252 string, which is a super set
		$str8859 = "\x93bendy quotes\x94";
		$str = '“bendy quotes”';
		$doc->loadHTML($str8859, "ISO-8859-1");
		$this->assertEquals($str, $doc->xpath->evaluate("string(//p/text())"));
	}
}
