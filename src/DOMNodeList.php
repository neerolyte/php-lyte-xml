<?php
namespace Lyte\XML;
/**
 * LyteDOMNodeList ensures that returned DOMNodes are actually LyteDOMNodes
 *
 * Implements Iterator because DOMNodeList implements Traversable, which is not
 * available to classes that exist outside of PHP's internal C code.
 */
class DOMNodeList extends XMLDecorator implements \Iterator {
	private $_pos = 0;

	public function current() {
		$raw = $this->_decorated->item($this->_pos);
		return $this->_decorate($raw);
	}
	public function next() { $this->_pos++; }
	public function key() { return $this->_pos; }
	/**
	 * Is the current position valid?
	 */
	public function valid() {
		return 
			$this->_pos < $this->_decorated->length 
			&& $this->_pos >= 0;
	}
	public function rewind() { $this->_pos = 0; }

	/**
	 * Provide a simple API to iterate over key value pair XML like:
	 *    <key1>value1</key1>
	 *    <key2>value2</key2>
	 *    ...
	 *    <keyN>valueN</keyN>
	 */
	public function toPairs() {
		return new DOMNodeListPairsIterator($this);
	}
}