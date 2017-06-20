<?php
namespace Lyte\XML;
/**
 * Some generalised decorating behaviour
 */
class XMLDecorator {
	/**
	 * What we're currently decorating
	 */
	protected $decorated = null;

	public function __construct($obj = null) {
		$this->decorated = $this->undecorate($obj);
	}

	/**
	 * Make the underlying decorated node accessible
	 */
	public function getDecorated() {
		return $this->decorated;
	}

	/**
	 * Catch calls to us and pass through to our decorated object
	 */
	public function __call($name, $origArgs) {
		$args = array();

		// check through the arguments and undecorate anything before passing through
		foreach ($origArgs as &$arg) {
			if ($arg instanceof XMLDecorator) {
				$args []= $arg->getDecorated();
			} else {
				$args []= $arg;
			}
		}

		$ret = call_user_func_array(array($this->decorated, $name), $args);
		return $this->decorate($ret);
	}

	/**
	 * Decorate one of the XML classes back to a LyteXML class
	 */
	public function decorate($obj) {
		// convert certain classes back to their decorated versions
		if ($obj instanceof \DOMDocument)
			return new DOMDocument($obj);
		if ($obj instanceof \DOMElement)
			return new DOMElement($obj);
		if ($obj instanceof \DOMNode)
			return new DOMNode($obj);
		if ($obj instanceof \DOMNodeList)
			return new DOMNodeList($obj);
		return $obj;
	}

	/**
	 * Reverse decoration (if applied)
	 */
	public function undecorate($obj) {
		if ($obj instanceof XMLDecorator)
			return $obj->decorated;
		else
			return $obj;
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		return $this->decorate($this->decorated->$name);
	}
}
