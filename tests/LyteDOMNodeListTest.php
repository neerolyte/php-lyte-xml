<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteDOMNodeListTest extends PHPUnit_Framework_TestCase {
	public function testItemReturnsLyteDOMNode() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new LyteDOMNodeList($doc->childNodes);

		$node = $list->item(0);

		$this->assertInstanceOf('LyteDOMNode', $node);
		$this->assertEquals('foo', $node->nodeName);
	}

	public function testTraversable() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new LyteDOMNodeList($doc->childNodes);

		$this->assertInstanceOf('Traversable', $list);
	}

	public function testIteratingOnLyteDOMNodeListReturnsLyteDOMNodes() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new LyteDOMNodeList($doc->childNodes);

		$items = 0;
		foreach ($list as $node) {
			$this->assertInstanceOf('LyteDOMNode', $node);
			$this->assertEquals('foo', $node->nodeName);
			$items++;
		}

		$this->assertEquals(1, $items);
	}
}
