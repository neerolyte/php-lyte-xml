<?php
/**
 * LyteDOMDocument decorates extra behaviour on to DOMDocument, but it
 * can't easilly inherit from it.
 */
class LyteDOMDocument extends LyteDOMNode {
	/**
	 * Optionally specify a real DOMDocument to construct this one from
	 */
	public function __construct(&$doc = null) {
		if ($doc !== null) {
			$this->_decorated =& $doc;
		} else {
			$this->_decorated = new DOMDocument();
		}
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		if ($name == 'firstChild') {
			$this->firstChild = new LyteDOMNode($this->_decorated->firstChild);
			return $this->firstChild;
		}
		if ($name == 'xpath') {
			$this->xpath = new DOMXPath($this->_decorated);
			return $this->xpath;
		}

		return $this->_decorated->$name;
	}
}
