<?php
namespace Lyte\XML;
use Lyte\XML\DOMNode;
/**
 * LyteDOMDocument decorates extra behaviour on to DOMDocument, but it
 * can't easilly inherit from it.
 */
class DOMDocument extends DOMNode {
	/**
	 * Optionally specify a real DOMDocument to construct this one from
	 */
	public function __construct(&$doc = null) {
		if ($doc !== null) {
			$this->_decorated =& $doc;
		} else {
			$this->_decorated = new \DOMDocument();
		}
	}

	/**
	 * Catch references to properties and pass them through to the decorated
	 * DOMDocument
	 */
	public function __get($name) {
		if ($name == 'xpath') {
			$this->xpath = new DOMXPath($this);
			return $this->xpath;
		}

		return parent::__get($name);
	}

	/**
	 * In LyteDOMNode we're overloading saveXML, so we have to
	 * un overload it again
	 */
	public function saveXML($node = null) {
		return $this->getDecorated()->saveXML(self::_undecorate($node));
	}

	/**
	 * Convert HTML characters to entities when so that we don't have to deal
	 * with how brokenly DOMDocument does this
	 *
	 * Blatently stolen from SmartDOMDocument:
	 * http://beerpla.net/projects/smartdomdocument-a-smarter-php-domdocument-class/#encoding-fix
	  *
	  * @param string $html
	  * @param string $encoding
	 */
	public function loadHTML($html, $encoding = "utf-8") {
		$encoding = strtolower($encoding);
		if ($encoding == 'iso-8859-1') $encoding = 'windows-1252';
		
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', $encoding);
		return $this->getDecorated()->loadHTML($html);
	}
}
