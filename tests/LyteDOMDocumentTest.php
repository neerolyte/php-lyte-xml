<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteDOMDocumentTest extends PHPUnit_Framework_TestCase {
	public function testLyteDOMDocumentBehavesLikeDOMDocument() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo>bar</foo>');

		$this->assertEquals('foo', $doc->firstChild->nodeName);
		$this->assertEquals('bar', $doc->firstChild->firstChild->wholeText);
	}

	public function testFirstChildIsLyteDOMNode() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');
		
		$this->assertInstanceOf('LyteDOMNode', $doc->firstChild);
	}

	public function testHasXPathProperty() {
		$doc = new LyteDOMDocument();

		$xpath = $doc->xpath;
		$this->assertInstanceOf('LyteDOMXPath', $xpath);

		// ensure we get the same instance each time
		$this->assertTrue($xpath === $doc->xpath);
	}

	public function testHasXPathWorksOnOurDoc() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');

		$nodeName = $doc->xpath->query('/foo')->item(0)->nodeName;
		$this->assertEquals('foo', $nodeName);
	}

	public function testCanAppendEitherChildtype() {
		$doc = new LyteDOMDocument();

		$node = $doc->getDecorated()->createElement('foo');
		$lnode = new LyteDOMNode($doc->createElement('bar'));

		$doc->appendChild($node);
		$doc->appendChild($lnode);

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<bar/>', $doc->saveXML($lnode));
	}

	public function testRemoveEitherChildtype() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<root><foo/><bar/></root>');

		$parent = $doc->getElementsByTagName('root')->item(0);

		$lnode = $doc->getElementsByTagName('foo')->item(0);
		$this->assertInstanceOf('LyteDOMElement', $lnode);
		$node = $doc->getDecorated()->getElementsByTagName('bar')->item(0);
		$this->assertInstanceOf('DOMElement', $node);

		$parent->removeChild($lnode);
		$parent->removeChild($node);
	}

	public function testCanSaveEitherNodeTypeAsXML() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');
		$lnode = new LyteDOMNode($doc->firstChild);
		$node = $lnode->getDecorated();

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<foo/>', $doc->saveXML($lnode));
	}

	public function testLoadHTMLUTF8() {
		$doc = new LyteDOMDocument();
		$str = '你好世界';
		$doc->loadHTML($str);
		$this->assertEquals($str, $doc->xpath->evaluate("string(//p/text())"));
	}

	public function testLoadHTMLISO_8859_1() {
		$doc = new LyteDOMDocument();
		// This is actually a Windows-1252 string, which is a super set
		$str8859 = "\x93bendy quotes\x94";
		$str = '“bendy quotes”';
		$doc->loadHTML($str8859, "ISO-8859-1");
		$this->assertEquals($str, $doc->xpath->evaluate("string(//p/text())"));
	}
}
