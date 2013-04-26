<?php
/**
 * Some generalised decorating behaviour
 */
class LyteXMLDecorator {
	/**
	 * What we're currently decorating
	 */
	protected $_decorated = null;

	/**
	 * Make the underlying decorated node accessible
	 */
	public function &getDecorated() {
		return $this->_decorated;
	}

	/**
	 * Catch calls to us and pass through to our decorated object
	 */
	public function __call($name, $origArgs) {
		$args = array();

		// check through the arguments and undecorate anything before passing through
		foreach ($origArgs as &$arg) {
			if ($arg instanceof LyteXMLDecorator) {
				$args []=& $arg->getDecorated();
			} else {
				$args []=& $arg;
			}
		}

		return call_user_func_array(array($this->_decorated, $name), $args);
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		return $this->_decorated->$name;
	}
}
