<?php
require_once(dirname(__FILE__).'/Autoload.php');
class LyteXMLWriterTest extends PHPUnit_Framework_TestCase {
	public function testInheritance() {
		$this->assertInstanceOf('XMLWriter', new LyteXMLWriter());
	}

	/**
	 * Ensure we get valid XML when nesting CDATA within another CDATA block
	 */
	public function testNestedCDataWithWriteCData() {
		$writer = new LyteXMLWriter();
		$writer->openMemory();
		$writer->writeCData('<![CDATA[a little bit of cdata]]>');
		$xml = $writer->flush();

		$this->assertEquals('<![CDATA[<![CDATA[a little bit of cdata]]]]><![CDATA[>]]>', $xml);
	}

	/**
	 * Automatically iconv translate between character encodings
	 */
	public function testCharacterEncodingFromISO_8859_1() {
		// Test both iso-8859-1 and Windows-1252 characters as Windows-1252
		// is a superset and the two are commonly misslabled.
		$src = "\x92\xb6\xf8";
		$wanted = "’¶ø";

		$writer = new LyteXMLWriter();
		$writer->openMemory();
		$writer->setSourceCharacterEncoding('iso-8859-1');
		$this->assertEquals($wanted, $writer->translateEncoding($src));

		$writer->setSourceCharacterEncoding('Windows-1252');
		$this->assertEquals($wanted, $writer->translateEncoding($src));
	}

	/**
	 * Test certain methods to make sure they translate via iconv automatically
	 */
	public function testCharacterEncodingMethods() {
		$tests = array(
			'$w->writeCData("foo");' => '<![CDATA[bar]]>',
			'$w->writeElement("foo", "baz");' => '<bar>qux</bar>',

			'$w->startElement("foo");'
				.'$w->writeAttribute("baz", "fred");'
				.'$w->endElement();' => '<bar qux="waldo"/>',

			'$w->text("foo");' => "bar",
			'$w->writeRaw("foo");' => "bar",

		);
		$map = array(
			array('foo', 'bar'),
			array('baz', 'qux'),
			array('fred', 'waldo'),
		);

		foreach ($tests as $code => $res) {
			$w = $this->getMock('LyteXMLWriter', array('translateEncoding'));
			$w->expects($this->any())
				->method('translateEncoding')
				->will($this->returnValueMap($map));

			$w->openMemory();
			eval($code);
			$xml = $w->flush();
			$this->assertEquals($res, $xml, "Failed for: $code");
		}
	}
}
