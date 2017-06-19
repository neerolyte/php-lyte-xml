<?php
namespace Lyte\XML;
/**
 * LyteDOMXPath decorates extra behaviour on to DOMXPath, but it
 * can't easilly inherit from it.
 */
class DOMXPath extends XMLDecorator {
	public function __construct(&$arg) {
		$this->_decorated = new \DOMXPath($arg->getDecorated());
	}
}
