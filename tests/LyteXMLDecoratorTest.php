<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteXMLDecoratorTest extends PHPUnit_Framework_TestCase {
	public function testDecoratingADOMNode() {
		$node = new DOMNode();
		$this->assertInstanceOf('LyteDOMNode', LyteXMLDecorator::_decorate($node));
	}
	public function testDecoratingADOMElement() {
		$el = new DOMElement('a');
		$this->assertInstanceOf('LyteDOMElement', LyteXMLDecorator::_decorate($el));
	}
	public function testDecoratingADOMDocument() {
		$doc = new DOMDocument();
		$this->assertInstanceOf('LyteDOMDocument', LyteXMLDecorator::_decorate($doc));
	}

	public function testRedecoration() {
		$el = new DOMElement('a');

		$lel = LyteXMLDecorator::_decorate($el);
		$this->assertTrue($el === $lel->getDecorated());

		$lel = LyteXMLDecorator::_decorate($lel);
		$this->assertTrue($el === $lel->getDecorated());
	}
}
