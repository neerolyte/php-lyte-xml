<?php
namespace Lyte\XML;
/**
 * LyteDOMNodeList ensures that returned DOMNodes are actually LyteDOMNodes
 *
 * Implements Iterator because DOMNodeList implements Traversable, which is not
 * available to classes that exist outside of PHP's internal C code.
 */
class DOMNodeList extends XMLDecorator implements \Iterator {
	private $pos = 0;

	public function current() {
		$raw = $this->decorated->item($this->pos);
		return $this->decorate($raw);
	}
	public function next() { $this->pos++; }
	public function key() { return $this->pos; }
	/**
	 * Is the current position valid?
	 */
	public function valid() {
		return 
			$this->pos < $this->decorated->length 
			&& $this->pos >= 0;
	}
	public function rewind() { $this->pos = 0; }

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