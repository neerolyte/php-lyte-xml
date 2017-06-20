<?php
namespace Lyte\XML;
/**
 * Iteratres over a LyteDOMNodeList as key value pairs
 */
class DOMNodeListPairsIterator implements \Iterator {
	private $list;
	private $pos = 0;

	public function __construct(DOMNodeList &$list) {
		$this->list = $list;
		$this->seekToDOMElement();
	}

	public function current() {
		return $this->list->item($this->pos)->nodeValue;
	}
    /**
     * Seek to the next DOMElement
     *
     * @return void
     */
	private function seekToDOMElement() {
		while ($this->valid() && !($this->list->item($this->pos) instanceof DOMElement)) {
			$this->pos++;
		}
	}
	public function next() { 
		$this->pos++;
		$this->seekToDOMElement();
	}
	public function key() { 
		return $this->list->item($this->pos)->nodeName; 
	}
	public function valid() {
		return 
			$this->pos < $this->list->length 
			&& $this->pos >= 0;
	}
	public function rewind() {
		$this->pos = 0;
		$this->seekToDOMElement();
	}
}
