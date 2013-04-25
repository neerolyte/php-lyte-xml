<?php
/**
 * Improvements to XMLWriter
 */
class LyteXMLWriter extends XMLWriter {
	/**
	 * Write content out within a CDATA encoded block                        
	 */
	public function writeCData($content) {                                   
		// This approach is fairly well documented: http://en.wikipedia.org/wiki/CDATA#Nesting 
		// Basically because we can't nest the CDATA tags (because a CDATA block can't contain 
		// the literal "]]>") we find occurances of "]]>" split it in to "]]" and ">" then     
		// close and reopen a new CDATA block in between.                                  
		$content = str_replace(']]>', ']]]]><![CDATA[>', $content);          
		return parent::writeCData($content);
	} // writeCData()   
}
