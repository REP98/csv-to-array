<?php 
declare(strict_types=1);
namespace CsvTo\Tests;

use PHPUnit\Framework\TestCase;

use CsvTo\Create;
use CsvTo\Tests\File;
use InvalidArgumentException;
/**
 * ToCsvTest
 */
class ToCsvTest extends TestCase
{
	use File;
	/** 
	 * @covers \CsvTo\Create
	 * @covers array_to_csv
	 */
	public function testCreate()
	{
		$csv = Create::start(
			$this->json['line'],
			$this->Directory."example2.csv"
		);
		$this->assertFileExists($this->Directory."example2.csv");
		$this->assertEquals($csv, array_to_csv($this->json['line'],$this->Directory."example2.csv"));
		$this->assertTrue($csv->setWithoutHeader(true));
		$this->assertFalse(array_to_csv([], $this->Directory."example2.csv"));
	}
	/** 
	 * @covers \CsvTo\Create
	 * @covers xml_to_csv
	 * @uses \CsvTo\Xml
	 */
	public function testCreateOfXml()
	{
		$csv = xml_to_csv(file_get_contents($this->Directory."example.xml"), $this->Directory."example3.csv");
		$this->assertInstanceOf(Create::class, $csv);

		$this->expectException(InvalidArgumentException::class);
		xml_to_csv("NO_SOY_XML", $this->Directory."example3.csv");

	}

	/** 
	 * @covers \CsvTo\Create
	 * @covers json_to_csv
	 * @uses \CsvTo\Json
	 * @uses \CsvTo\Xml
	 */
	public function testCreateOfJson()
	{
		$j = json_encode($this->json);
		$csv = json_to_csv($j, $this->Directory."example4.csv");
		$this->assertInstanceOf(Create::class, $csv);

		$this->expectException(InvalidArgumentException::class);
		json_to_csv("JSON:NOTVALID", $this->Directory."example4.csv");
	}
}
?>