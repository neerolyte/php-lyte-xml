<?php
class LyteDOMNode {
	private $_decorated = null;

	public function __construct(&$node = null) {
		$this->_decorated =& $node;
	}

	/**
	 * Catch calls to us and pass through to our decorated DOMDocument
	 */
	public function __call($name, $args) {
		call_user_func_array(array($this->_decorated, $name), $args);
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		return $this->_decorated->$name;
	}
}
