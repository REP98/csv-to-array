<?php 
namespace CsvTo\Tests;

trait File {
	protected $fileCsv = "";
	protected $Directory = "";
	protected $json = [];

	protected function setUp(): void
	{
		$this->Directory = __DIR__.DIRECTORY_SEPARATOR."file".DIRECTORY_SEPARATOR;
		$this->fileCsv = $this->Directory."example.csv";
		$this->json = [
			"header" => [
				'ID', 'User', 'Name', 'LastName', 'Email', 'Date'
			],
			'line' => [
				[
					"ID" => "1",
					"User" => 'Usuario1',
					"Name" => "Pedro",
					"LastName" => "Perez",
					"Email" => "pedro@perez.com",
					"Date" => "12/10/22"
				],
				[
					"ID" => "2",
					"User" => 'Usuario2',
					"Name" => "Jhon",
					"LastName" => "Doe",
					"Email" => "jhondoe@gmail.com",
					"Date" => "28/02/22"
				]
			]
		];
	}
}
?>