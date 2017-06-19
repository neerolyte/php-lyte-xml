<?php
namespace Lyte\XML\Tests;
use Lyte\XML\XMLReader;
class XMLReaderTest extends TestCase {

	public function testInheritance() {
		$this->assertInstanceOf('\\XMLReader', new XMLReader());
	}

	public function testExpandedNodeHasOwnerDocument() {
		$reader = new XMLReader();
		$reader->xml('<foo>bar</foo>');
		$reader->read();
		$node = $reader->expand();                                                                                             
		$this->assertInstanceOf('Lyte\\XML\\DOMDocument', $node->ownerDocument);
	}

	public function testExpandedNodesOwnerDocumentHasNode() {
		$reader = new XMLReader();
		$reader->xml('<foo>bar</foo>');
		$reader->read();
		$node = $reader->expand();
		$this->assertContains('<foo>bar</foo>', $node->ownerDocument->saveXML());
		$this->assertEquals('<foo>bar</foo>', $node->ownerDocument->saveXML($node->getDecorated()));
	}

	public function testExpandedNodeIsALyteDOMNode() {
		$reader = new XMLReader();
		$reader->xml('<foo>bar</foo>');
		$reader->read();
		$node = $reader->expand();
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $node);
	}
}
