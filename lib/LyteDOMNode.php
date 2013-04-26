<?php
class LyteDOMNode extends LyteXMLDecorator {
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
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		if ($name == 'ownerDocument') {
			return new LyteDOMDocument($this->_decorated->ownerDocument);
		}

		return parent::__get($name);
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
