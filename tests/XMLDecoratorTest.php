<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMNode;
use Lyte\XML\XMLDecorator;
use Lyte\XML\DOMElement;
class LyteXMLDecoratorTest extends TestCase {
	public function testDecoratingADOMNode() {
		$node = new \DOMNode();
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', XMLDecorator::_decorate($node));
	}
	public function testDecoratingADOMElement() {
		$el = new \DOMElement('a');
		$this->assertInstanceOf('Lyte\\XML\\DOMElement', XMLDecorator::_decorate($el));
	}
	public function testDecoratingADOMDocument() {
		$doc = new \DOMDocument();
		$this->assertInstanceOf('Lyte\\XML\\DOMDocument', XMLDecorator::_decorate($doc));
	}

	public function testRedecoration() {
		$el = new \DOMElement('a');

		$lel = XMLDecorator::_decorate($el);
		$this->assertTrue($el === $lel->getDecorated());

		$lel = XMLDecorator::_decorate($lel);
		$this->assertTrue($el === $lel->getDecorated());
	}
}
