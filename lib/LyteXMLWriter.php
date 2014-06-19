<?php
/**
 * Improvements to XMLWriter
 */
class LyteXMLWriter extends XMLWriter {
	/**
	 * If we are translating the character encoding
	 */
	private $_sourceCharacterEncoding = null;

	/**
	 * We will optionally translate from a source character encoding other than
	 * UTF-8 as the built in XMLWriter does not
	 */
	public function setSourceCharacterEncoding($encoding) {
		// Windows-1252 is a superset of iso-8859-1 and they are commonly
		// misslabled
		if (strtolower($encoding) == 'iso-8859-1')
			$encoding = 'Windows-1252';

		$this->_sourceCharacterEncoding = $encoding;
	}

	/**
	 * Translate character encoding using the previously supplied encoding to
	 * setSourceCharacterEncoding()
	 */
	public function translateEncoding($data) {
		if ($this->_sourceCharacterEncoding === null)
			return $data;

		return iconv($this->_sourceCharacterEncoding, 'utf-8', $data);
	}

	/**
	 * Write content out within a CDATA encoded block                        
	 */
	public function writeCData($content) {                                   
		// This approach is fairly well documented: http://en.wikipedia.org/wiki/CDATA#Nesting 
		// Basically because we can't nest the CDATA tags (because a CDATA block can't contain 
		// the literal "]]>") we find occurances of "]]>" split it in to "]]" and ">" then     
		// close and reopen a new CDATA block in between.                                  
		$content = str_replace(']]>', ']]]]><![CDATA[>', $content);
		$content = $this->translateEncoding($content);
		return parent::writeCData($content);
	} // writeCData()

	/**
	 * Writes a full element tag.
	 */
	public function writeElement($name, $content = '') {
		$name = $this->translateEncoding($name);
		$content = $this->translateEncoding($content);
		return parent::writeElement($name, $content);
	}

	/**
	 * Writes a full attribute.
	 */
	public function writeAttribute($name, $value) {
		$name = $this->translateEncoding($name);
		$value = $this->translateEncoding($value);
		return parent::writeAttribute($name, $value);
	}

	/**
	 * Starts an element.
	 */
	public function startElement($name) {
		$name = $this->translateEncoding($name);
		return parent::startElement($name);
	}

	/**
	 * Writes a text.
	 */
	public function text($content) {
		$content = $this->translateEncoding($content);
		return parent::text($content);
	}

	/**
	 * Writes a raw xml text.
	 */
	public function writeRaw($content) {
		$content = $this->translateEncoding($content);
		return parent::writeRaw($content);
	}
}
