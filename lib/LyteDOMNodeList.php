<?php
/**
 * LyteDOMNodeList ensures that returned DOMNodes are actually LyteDOMNodes
 *
 * Implements Iterator because DOMNodeList implements Traversable, which is not
 * available to classes that exist outside of PHP's internal C code.
 */
class LyteDOMNodeList extends LyteXMLDecorator implements Iterator {
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
		return new LyteDOMNodeListPairsIterator($this);
	}
}

/**
 * Iteratres over a LyteDOMNodeList as key value pairs
 */
class LyteDOMNodeListPairsIterator implements Iterator {
	private $_list;
	private $_pos = 0;

	public function __construct(LyteDOMNodeList &$list) {
		$this->_list =& $list;
		$this->_seek();
	}

	public function current() {
		return $this->_list->item($this->_pos)->nodeValue;
	}
	private function _seek() {
		// iterate past non LyteDOMElement nodes
		while ($this->valid() && !($this->_list->item($this->_pos) instanceof LyteDOMElement)) {
			$this->_pos++;
		}
	}
	public function next() { 
		$this->_pos++;
		$this->_seek();
	}
	public function key() { 
		return $this->_list->item($this->_pos)->nodeName; 
	}
	public function valid() {
		return 
			$this->_pos < $this->_list->length 
			&& $this->_pos >= 0;
	}
	public function rewind() {
		$this->_pos = 0;
		$this->_seek();
	}
}
