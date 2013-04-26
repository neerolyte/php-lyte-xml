<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteDOMElementTest extends PHPUnit_Framework_TestCase {
	public function testInheritance() {
		$el = new LyteDOMElement();
		$this->assertInstanceOf('LyteDOMNode', $el);
	}

	public function testRedecoration() {
		$el = new DOMElement('a');

		$lel = new LyteDOMElement($el);
		$this->assertTrue($el === $lel->getDecorated());

		$lel = new LyteDOMElement($lel);
		$this->assertTrue($el === $lel->getDecorated());
	}
}
