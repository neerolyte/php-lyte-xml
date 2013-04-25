<?php
require_once(dirname(dirname(__FILE__))."/vendor/autoload.php");
require_once("PHPUnit/Autoload.php");

require_once(dirname(dirname(__FILE__)).'/Autoload.php');

class LyteXMLReaderTest extends PHPUnit_Framework_TestCase {
	public function testExpandedNodeHasOwnerDocument() {
		$reader = new LyteXMLReader();
		$reader->xml('<foo>bar</foo>');
		$reader->read();
		$node = $reader->expand();                                                                                             
		$this->assertInstanceOf('DOMDocument', $node->ownerDocument);
	}

	public function testExpandedNodesOwnerDocumentHasNode() {
		$reader = new LyteXMLReader();
		$reader->xml('<foo>bar</foo>');
		$reader->read();
		$node = $reader->expand();                                                                                             
		$this->assertContains('<foo>bar</foo>', $node->ownerDocument->saveXML());
		$this->assertEquals('<foo>bar</foo>', $node->ownerDocument->saveXML($node));
	}
}
