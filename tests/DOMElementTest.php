<?php
namespace Lyte\XML\Tests;
use Lyte\XML\DOMElement;
class DOMElementTest extends TestCase {
	public function testInheritance() {
		$el = new DOMElement('a');
		$this->assertInstanceOf('Lyte\\XML\\DOMNode', $el);
	}

	public function testRedecoration() {
		$el = new \DOMElement('a');

		$lel = new DOMElement($el);
		$this->assertTrue($el === $lel->getDecorated());

		$lel = new DOMElement($lel);
		$this->assertTrue($el === $lel->getDecorated());
	}

	public function testCreatingElementWithAName() {
		$el = new DOMElement('a');
		$this->assertEquals('a', $el->nodeName);
	}
}
