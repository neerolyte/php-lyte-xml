<?php
require_once(dirname(__FILE__).'/Autoload.php');
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

	public function testCanAppendEitherChildtype() {
		$doc = new LyteDOMDocument();
		$parent = new LyteDOMNode($doc->createElement('foo'));

		$doc->getDecorated()->appendChild($parent->getDecorated());

		$node = $doc->getDecorated()->createElement('foo');
		$lnode = new LyteDOMNode($doc->createElement('bar'));

		$parent->appendChild($node);
		$parent->appendChild($lnode);

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<bar/>', $doc->saveXML($lnode));
	}

	public function testChildNodesIsALyteDOMNodeList() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');
		$children = $doc->childNodes;

		$this->assertInstanceOf('LyteDOMNodeList', $children);

		$count = 0;
		foreach ($children as $child) {
			$this->assertEquals('foo', $child->nodeName);
			$count++;
		}
		$this->assertEquals(1, $count);
	}
}
