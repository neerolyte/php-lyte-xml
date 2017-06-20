<?php
namespace Lyte\XML;
class DOMNode extends XMLDecorator {
	/**
	 * Perform a contextualised XPath::query() from this node
	 */
	public function xPathQuery($expression) {
		return $this->ownerDocument->xpath->query($expression, $this->decorated);
	}

	/**
	 * Perform a contextualised XPath::evaluate() from this node
	 */
	public function xPathEvaluate($expression) {
		return $this->ownerDocument->xpath->evaluate($expression, $this->decorated);
	}

	/**
	 * Provide a saveXML on every node that will just save the XML
	 * for the current node
	 */
	public function saveXML() {
		return $this->ownerDocument->saveXML($this);
	}
}
