<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMNode;
use Lyte\XML\DOMDocument;
class DOMNodeTest extends TestCase {
	public function testNestedDecoration() {
		$realNode = new \DOMNode();

		$node = new DOMNode($realNode);
		$this->assertTrue($node->getDecorated() === $realNode);

		// decorate ourselves
		$node = new DOMNode($node);
		$this->assertTrue($node->getDecorated() === $realNode);
	}

	public function testOwnerDocumentIsLyteDOMDocument() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');

		$node = $doc->firstChild;
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $node);
		$this->assertInstanceOf('Lyte\\XML\\DOMDocument', $node->ownerDocument);
	}

	public function testHasContextifiedXPathQuery() {
		$doc = new DOMDocument();
		$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');

		$node = new DOMNode($doc->firstChild);
		$res = $node->xPathQuery('foo/text()');
		$this->assertEquals(2, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertEquals('two', $res->item(1)->wholeText);
		
		$node = new DOMNode($doc->firstChild->firstChild);
		$res = $node->xPathQuery('text()');
		$this->assertEquals(1, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertInstanceOf('Lyte\\XML\\DOMNodeList', $res);
	}

	public function testHasContextifiedXPathEvaluate() {
		$doc = new DOMDocument();
		$doc->loadXML('<root><foo>one</foo><foo>two</foo></root>');

		$node = new DOMNode($doc->firstChild);
		$res = $node->xPathEvaluate('foo/text()');
		$this->assertEquals(2, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertEquals('two', $res->item(1)->wholeText);
		
		$node = new DOMNode($doc->firstChild->firstChild);
		$res = $node->xPathEvaluate('text()');
		$this->assertEquals(1, $res->length);
		$this->assertEquals('one', $res->item(0)->wholeText);
		$this->assertInstanceOf('Lyte\\XML\\DOMNodeList', $res);
	}

	public function testCanAppendEitherChildtype() {
		$doc = new DOMDocument();
		$parent = new DOMNode($doc->createElement('foo'));

		$doc->getDecorated()->appendChild($parent->getDecorated());

		$node = $doc->getDecorated()->createElement('foo');
		$lnode = new DOMNode($doc->createElement('bar'));

		$parent->appendChild($node);
		$parent->appendChild($lnode);

		$this->assertEquals('<foo/>', $doc->saveXML($node));
		$this->assertEquals('<bar/>', $doc->saveXML($lnode));
	}

	public function testChildNodesIsADOMNodeList() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$children = $doc->childNodes;

		$this->assertInstanceOf('Lyte\\XML\\DOMNodeList', $children);

		$count = 0;
		foreach ($children as $child) {
			$this->assertEquals('foo', $child->nodeName);
			$count++;
		}
		$this->assertEquals(1, $count);
	}

	function testNodesCanSaveTheirOwnXML() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');

		$this->assertEquals('<foo/>', $doc->firstChild->saveXML());
	}
}
