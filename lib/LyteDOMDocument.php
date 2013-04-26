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
		$this->_decorated =& $doc;
	}

	private function _checkDecorating() {
		if ($this->_decorated === null) {
			$this->_decorated = new DOMDocument();
		}
	}

	/**
	 * DOMDocuments might not actually be decorated up front,
	 * so we lazily set one up here
	 */
	public function &getDecorated() {
		$this->_checkDecorating();
		return parent::getDecorated();
	}

	/**
	 * Catch calls to us and pass through to our decorated DOMDocument
	 */
	public function __call($name, $args) {
		$this->_checkDecorating();

		return call_user_func_array(array($this->_decorated, $name), $args);
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		$this->_checkDecorating();

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
