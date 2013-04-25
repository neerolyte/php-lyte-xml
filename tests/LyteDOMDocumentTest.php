<?php
require_once(dirname(dirname(__FILE__))."/vendor/autoload.php");
require_once("PHPUnit/Autoload.php");

require_once(dirname(dirname(__FILE__)).'/Autoload.php');

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
}
