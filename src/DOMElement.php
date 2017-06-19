<?php
namespace Lyte\XML;
class DOMElement extends DOMNode {
	/**
	 * Elements can be created with a string provided, which becomes the
	 * tag name
	 */
	public function __construct($arg) {
		if (is_string($arg)) {
			$arg = new \DOMElement($arg);
		}
		parent::__construct($arg);
	}
}
