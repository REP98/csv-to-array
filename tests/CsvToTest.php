<?php 
declare(strict_types=1);
namespace CsvTo\Tests;

use PHPUnit\Framework\TestCase;
use CsvTo\Arr;
use CsvTo\Json;
use CsvTo\Load;
use CsvTo\Xml;
use CsvTo\Tests\File;

class CsvToTest extends TestCase
{
	use File;

	/**
	 * @covers \CsvTo\Load
	 */
	public function testLoad() {
		$l = Load::file($this->fileCsv);
		$s = $l->setConfig(['withoutHeader' => true]);
		
		$this->assertTrue(is_array($l->getLines()));

		$this->assertInstanceOf(Load::class, $s);

		$this->assertEquals([], $s->getHeaders());

		$this->assertEquals($this->json['line'], $l->getLines());

		$this->assertEquals($this->json['line'][0], $l->getLine(0));
	}
	/**
	 * @covers \CsvTo\Arr
	 * @covers csv_to_array
	 * @uses \CsvTo\Load
	 */
	public function testArr()
	{
		$a = Arr::file($this->fileCsv);

		$this->assertEquals($this->json['line'][0], $a[0]);
		$this->assertFalse(empty($a[1]));
		$d = [
			'ID' => "3",
			"User" => "Username3",
			"Name" => "Jhon",
			"LastName" => "Ash",
			"Date" => "12/01/2001"
		];

		$a[2] = $d;
		$this->assertEquals($d, $a[2]);
		$c = [
			'ID' => "4",
			"User" => "Username4",
			"Name" => "Marta",
			"LastName" => "Gomez",
			"Date" => "15/06/1981"
		];

		$a[] = $c;	
		
		$this->assertEquals($c, $a[3]);

		unset($a[3]);

		$this->assertEmpty($a[3]);

		unset($a[2]);

		$this->assertEquals([
			"header" => $a->getHeaders(),
			"lines" => $a->getLines()
		], csv_to_array($this->fileCsv));
	}
	/**
	 * @covers \CsvTo\Json
	 * @covers csv_to_json
	 * @uses \CsvTo\Load
	 */
	public function testJson()
	{
		$json = Json::file($this->fileCsv);
		$Classjson = new Json($this->fileCsv, ['setHeaderJson' => true]);

		$this->assertJsonStringEqualsJsonString(json_encode($this->json), $json);
		$this->assertJsonStringEqualsJsonString(
			'{"header":["ID","User","Name","LastName","Email","Date"],"lines":{"ID":"2","User":"Usuario2","Name":"Jhon","LastName":"Doe","Email":"jhondoe@gmail.com","Date":"28\/02\/22"}}', 
			$Classjson->getData(1));
		$this->assertTrue(Json::isJson($json));
		
		$this->assertJsonStringEqualsJsonString($json, csv_to_json($this->fileCsv));
	}
	/**
	 * @covers \CsvTo\Json
	 * @covers csv_to_json_responce
	 * @uses \CsvTo\Json
	 * @uses \CsvTo\Load
	 * @runInSeparateProcess 
	 */
	public function testJsonResponce($value='')
	{
		$xml = csv_to_json_responce($this->fileCsv);
		$headers = php_sapi_name() === 'cli' ? xdebug_get_headers() : headers_list();
		$this->assertTrue(in_array("Content-Type: application/json; charset=utf-8", $headers));
	}
	/**
	 * @covers \CsvTo\Xml
	 * @covers csv_to_xml
	 * @covers csv_to_export_xml
	 * @uses \CsvTo\Load
	 */
	public function testXml()
	{
		$xml = Xml::file($this->fileCsv);
		$xml2 = csv_to_xml($this->fileCsv);
		$this->assertXmlStringEqualsXmlString($xml,$xml2);
		$this->assertTrue(Xml::isXml($xml2));
		$x2 = csv_to_export_xml($this->fileCsv, $this->Directory, ["encoding" => 'ISO-8859-1']);
		$this->assertFileExists($this->Directory."example.xml");
		$this->assertXmlStringEqualsXmlFile($this->Directory."example.xml", $x2);
		$this->assertArrayHasKey("lines", Xml::toArray($xml2));
	}
	

	/**
	 * @covers \CsvTo\Xml
	 * @covers csv_to_xml_responce
	 * @uses \CsvTo\Xml
	 * @uses \CsvTo\Load
	 * @runInSeparateProcess 
	 */
	public function testXmlResponce($value='')
	{
		$xml = csv_to_xml_responce($this->fileCsv);
		$headers = php_sapi_name() === 'cli' ? xdebug_get_headers() : headers_list();
		$this->assertTrue(in_array("content-type: application/xml; charset=UTF-8", $headers));
	}
}
?>