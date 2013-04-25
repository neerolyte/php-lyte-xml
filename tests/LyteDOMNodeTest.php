<?php
require_once(dirname(dirname(__FILE__))."/vendor/autoload.php");
require_once("PHPUnit/Autoload.php");

require_once(dirname(dirname(__FILE__)).'/Autoload.php');

class LyteDOMNodeTest extends PHPUnit_Framework_TestCase {
	public function testCanInstantiate() {
		$node = new LyteDOMNode(new DOMNode());
	}
}
