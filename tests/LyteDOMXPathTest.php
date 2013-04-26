<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteDOMXPathTest extends PHPUnit_Framework_TestCase {

	public function testXPathCanQuery() {
		$doc = new LyteDOMDocument();
		$doc->loadXML('<foo/>');
		$xpath = new LyteDOMXPath($doc);

		$nodes = $xpath->query('.');
		$this->assertEquals(1, $nodes->length);
		$this->assertEquals('foo', $nodes->item(0)->nodeName);
	}
}
