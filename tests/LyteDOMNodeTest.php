<?php
require_once(dirname(dirname(__FILE__))."/vendor/autoload.php");
require_once("PHPUnit/Autoload.php");

require_once(dirname(dirname(__FILE__)).'/Autoload.php');

class LyteDOMNodeTest extends PHPUnit_Framework_TestCase {

	public function testCanInstantiate() {
		$node = new LyteDOMNode(new DOMNode());
	}

	public function testNestedDecoration() {
		$realNode = new DOMNode();

		$node = new LyteDOMNode($realNode);
		$this->assertTrue($node->getDecorated() === $realNode);

		// decorate ourselves
		$node = new LyteDOMNode($node);
		$this->assertTrue($node->getDecorated() === $realNode);
	}

	public function testOwnerDocumentIsLyteDOMDocument() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');

		$node = $doc->firstChild;
		$this->assertInstanceOf('LyteDOMNode', $node);
		$this->assertInstanceOf('LyteDOMDocument', $node->ownerDocument);
	}

	public function testHasContextifiedXPathQuery() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');

		$node = new LyteDOMNode($doc->firstChild);
		$res = $node->xPathQuery('foo/text()');
		$this->assertEquals(2, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertEquals('two', $res->item(1)->wholeText);
		
		$node = new LyteDOMNode($doc->firstChild->firstChild);
		$res = $node->xPathQuery('text()');
		$this->assertEquals(1, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
	}

	public function testHasContextifiedXPathEvaluate() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');

		$node = new LyteDOMNode($doc->firstChild);
		$res = $node->xPathEvaluate('foo/text()');
		$this->assertEquals(2, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertEquals('two', $res->item(1)->wholeText);
		
		$node = new LyteDOMNode($doc->firstChild->firstChild);
		$res = $node->xPathEvaluate('text()');
		$this->assertEquals(1, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
	}
}
