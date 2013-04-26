<?php
spl_autoload_register(function($class) {
	if (in_array($class, array(
		'LyteXMLWriter', 'LyteXMLReader', 'LyteDOMDocument', 'LyteDOMNode',
		'LyteDOMXPath', 'LyteXMLDecorator',
	))) {
		require_once(dirname(__FILE__)."/lib/$class.php");
	}
});
