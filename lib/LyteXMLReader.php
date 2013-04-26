<?php
/**
 * Improvements to XMLReader
 */
class LyteXMLReader extends XMLReader {

	/**
	 * Perform a normal expand(), but ensure there's a DOMDocument
	 * attached to ownerDocument of the produced DOMNode
	 */
	public function expand(DOMNode &$basenode = null) {
		$node = parent::expand($basenode);

		// synthesize the ownerDocument if it's not filled out
		if ($node->ownerDocument === null) {
			$doc = new DOMDocument();
			$node = $doc->importNode($node, true);
			$doc->appendChild($node);
		}

		// decorate the expanded node
		return new LyteDOMNode($node);
	}
}
