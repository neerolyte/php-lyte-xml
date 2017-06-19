<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMXPath;
use Lyte\XML\DOMDocument;
class DOMXPathTest extends TestCase {

	public function testXPathCanQuery() {
		$doc = new DOMDocument();
		$doc->loadXML('<foo/>');
		$xpath = new DOMXPath($doc);

		$nodes = $xpath->query('.');
		$this->assertEquals(1, $nodes->length);
		$this->assertEquals('foo', $nodes->item(0)->nodeName);
	}
}
