<?php
/**
 * LyteDOMDocument decorates extra behaviour on to DOMDocument, but it
 * can't easilly inherit from it.
 */
class LyteDOMDocument {
	private $_decorated = null;

	private function _checkDecorating() {
		if ($this->_decorated === null) {
			$this->_decorated = new DOMDocument();
		}
	}

	/**
	 * Catch calls to us and pass through to our decorated DOMDocument
	 */
	public function __call($name, $args) {
		$this->_checkDecorating();

		call_user_func_array(array($this->_decorated, $name), $args);
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

		return $this->_decorated->$name;
	}
}
