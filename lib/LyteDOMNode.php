<?php
class LyteDOMNode extends LyteXMLDecorator {
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
