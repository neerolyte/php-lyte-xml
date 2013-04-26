<?php
/**
 * Some generalised decorating behaviour
 */
abstract class LyteXMLDecorator {
	/**
	 * What we're currently decorating
	 */
	protected $_decorated = null;

	public function __construct($obj = null) {
		if ($obj instanceof LyteXMLDecorator)
			$this->_decorated =& $obj->_decorated;
		else
			$this->_decorated =& $obj;
	}

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

		$ret = call_user_func_array(array($this->_decorated, $name), $args);
		return $this->_decorate($ret);
	}

	/**
	 * Decorate one of the XML classes back to a LyteXML class
	 */
	public static function _decorate(&$obj) {
		// convert certain classes back to their decorated versions
		if ($obj instanceof DOMDocument)
			return new LyteDOMDocument($obj);
		if ($obj instanceof DOMElement)
			return new LyteDOMElement($obj);
		if ($obj instanceof DOMNode)
			return new LyteDOMNode($obj);
		if ($obj instanceof DOMNodeList)
			return new LyteDOMNodeList($obj);
		return $obj;
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		return $this->_decorate($this->_decorated->$name);
	}
}
