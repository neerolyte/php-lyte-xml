<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMNode;
use Lyte\XML\XMLDecorator;
use Lyte\XML\DOMElement;
class XMLDecoratorTest extends TestCase {
	public function testDecoratingADOMNode() {
		$node = new \DOMNode();
		$decorator = new XMLDecorator();
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $decorator->decorate($node));
	}
	public function testDecoratingADOMElement() {
		$el = new \DOMElement('a');
		$decorator = new XMLDecorator();
		$this->assertInstanceOf('Lyte\\XML\\DOMElement', $decorator->decorate($el));
	}
	public function testDecoratingADOMDocument() {
		$doc = new \DOMDocument();
		$decorator = new XMLDecorator();
		$this->assertInstanceOf('Lyte\\XML\\DOMDocument', $decorator->decorate($doc));
	}

	public function testRedecoration() {
		$el = new \DOMElement('a');
		$decorator = new XMLDecorator();

		$lel = $decorator->decorate($el);
		$this->assertTrue($el === $lel->getDecorated());

		$lel = $decorator->decorate($lel);
		$this->assertTrue($el === $lel->getDecorated());
	}
}
