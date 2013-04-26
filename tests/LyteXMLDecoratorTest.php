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
}
