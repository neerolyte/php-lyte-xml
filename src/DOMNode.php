<?php
namespace Lyte\XML;
class DOMNode extends XMLDecorator {
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
		return $this->getDecorated()->appendChild(self::_undecorate($child));
	}

	/**
	 * Provide a saveXML on every node that will just save the XML
	 * for the current node
	 */
	public function saveXML($node = null) {
		return $this->ownerDocument->saveXML($this);
	}
}
