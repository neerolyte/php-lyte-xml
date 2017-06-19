<?php
namespace Lyte\XML;
class DOMElement extends DOMNode {
	/**
	 * Elements can be created with a string provided, which becomes the
	 * tag name
	 */
	public function __construct($arg) {
		if (is_string($arg)) {
			$this->_decorated = new \DOMElement($arg);
		} else {
			parent::__construct($arg);
		}
	}
}
