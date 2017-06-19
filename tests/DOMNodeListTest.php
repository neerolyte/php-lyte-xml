<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMNodeList;
use Lyte\XML\DOMDocument;
class DOMNodeListTest extends TestCase {
	public function testItemReturnsLyteDOMNode() {
		$doc = new \DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new DOMNodeList($doc->childNodes);

		$node = $list->item(0);

		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $node);
		$this->assertEquals('foo', $node->nodeName);
	}

	public function testTraversable() {
		$doc = new \DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new DOMNodeList($doc->childNodes);

		$this->assertInstanceOf('Traversable', $list);
	}

	public function testIteratingOnLyteDOMNodeListReturnsLyteDOMNodes() {
		$doc = new \DOMDocument();
		$doc->loadXML('<foo/>');
		$list = new DOMNodeList($doc->childNodes);

		$items = 0;
		foreach ($list as $node) {
			$this->assertInstanceOf('Lyte\\XML\\DOMNode', $node);
			$this->assertEquals('foo', $node->nodeName);
			$items++;
		}

		$this->assertEquals(1, $items);
	}

	/**
	 * Sometimes I want to just iterate over a node that should only contain
	 * XML similar to:
	 *    <key1>value1</key1>
	 *    <key2>value2</key2>
	 *    ...
	 *    <keyN>valueN</keyN>
	 *
	 * Lets provide a nice short hand for that
	 */
	public function testCanIterateFlatKeyValuePairs() {
		$doc = new DOMDocument();
		$xml = "
			<root>
				<key1>value1</key1>
				<key2>value2</key2>
				<key3>value3</key3>
			</root>";

		$doc->loadXML($xml);
		$node = $doc->firstChild;

		$item = 0;
		foreach ($node->childNodes->toPairs() as $k => $v) {
			$item++;
			$this->assertEquals("key$item", $k);
			$this->assertEquals("value$item", $v);
		}

		$this->assertEquals(3, $item);
	}
}
