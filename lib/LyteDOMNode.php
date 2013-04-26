<?php
class LyteDOMNode {
	protected $_decorated = null;

	/**
	 * Optionally decorate a DOMNode or a LyteDOMNode
	 */
	public function __construct($node = null) {
		if (!is_null($node)) {
			if ($node instanceof LyteDOMNode) {
				$this->_decorated =& $node->_decorated;
			} else {
				$this->_decorated =& $node;
			}
		} else {
			throw new ArgumentException("Node can not be null");
		}
	}

	/**
	 * Make the underlying decorated node accessible
	 */
	public function &getDecorated() {
		return $this->_decorated;
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
		if ($name == 'ownerDocument') {
			return new LyteDOMDocument($this->_decorated->ownerDocument);
		}

		return $this->_decorated->$name;
	}

	/**
	 * Perform a contextualised XPath::query() from this node
	 */
	public function xPathQuery($expression) {
		return $this->ownerDocument->xpath->query($expression, $this->_decorated);
	}

	/**
	 * Perform a contextualised XPath::evaluate() from this node
	 */
	public function xPathEvaluate($expression) {
		return $this->ownerDocument->xpath->evaluate($expression, $this->_decorated);
	}

	/**
	 * Ensure that appended children are actually the decorated children
	 */
	public function appendChild(&$child) {
		$c =& $child;
		if ($child instanceof LyteDOMNode) {
			$c = $child->getDecorated();
		}
		return $this->_decorated->appendChild($c);
	}
}
