<?php
declare(strict_types=1);

use CsvTo\Arr;
use CsvTo\Create;
use CsvTo\Json;
use CsvTo\Xml;
/**
 * Funciones de Conversion
 * CSV ==> Array
 */

if (!function_exists('csv_to_array')) {
	/**
	 * Transforma un CSV a un Array
	 * @param  string $file
	 * @param  array  $config
	 * @return \CsvTo\Arr
	 */
	function csv_to_array(string $file, array $config = []): array
	{
		$a = Arr::file($file, $config);
		return [
			'header' => $a->getHeaders(),
			'lines' => $a->getLines()
		];
	}
}
// CSV ==> JSON
if (!function_exists("csv_to_json")) {
	/**
	 * Transforma un CSV a JSON
	 * @param  string $file  
	 * @param  array  $config 
	 * @return string
	 */
	function csv_to_json(string $file, array $config = []): string
	{
		return Json::file($file, $config);
	}
}

if (!function_exists("csv_to_json_responce")) {
	/**
	 * Transforma un CSV en JSON y lo retorna con las cabeceras correspondientes
	 * @param  string $file   
	 * @param  array  $config 
	 * @return string
	 */
	function csv_to_json_responce(string $file, array $config = [])
	{
		$config = array_merge($config, [
			"setHeaderJson" => true
		]);
		return Json::file($file, $config);
	}
}
// CSV ==> XML
if (!function_exists("csv_to_xml")) {
	/**
	 * Transforma un CSV en XML
	 * @param  string $file   
	 * @param  array  $config 
	 * @return string
	 */
	function csv_to_xml(string $file, array $config = []): string
	{
		return Xml::file($file, $config);
	}
}

if (!function_exists("csv_to_xml_responce")) {
	/**
	 * Transforma un CSV en XML y lo retorna con las cabeceras correspondientes
	 * @param  string $file
	 * @param  array  $config
	 * @return string
	 */
	function csv_to_xml_responce(string $file, array $config = []) {
		$config = array_merge($config, [
			"setHeaderXml" => true
		]);
		return Xml::file($file, $config);
	}
}
if (!function_exists("csv_to_export_xml")) {
	/**
	 * Exporta un CSV en formato XML
	 *
	 * @param  string $file   Ruta del CSV
	 * @param  string $path   Ruta del directorio, El XML generado conserva el nombre del CSV
	 * @param  array  $config Configuraciones
	 * @return string
	 */
	function csv_to_export_xml(string $file, string $path, array $config = []) {
		$config['export'] = $path;
		return Xml::file($file, $config);
	}
}
// ARRAY ==> CSV
if (!function_exists('array_to_csv')) {
	/**
	 * Convierte un array en CSV
	 * @param  array  $data   
	 * @param  string $path   
	 * @param  array  $config 
	 * @return \CsvTo\Create 
	 */
	function array_to_csv(array $data, string $path, array $config = []) {
		if (!is_array($data) || empty($data)) {
			return false;
		}
		$config['path'] = $path;
		return Create::start($data, $config);
	}
}
// XML ==> CSV
if (!function_exists('xml_to_csv')) {
	/**
	 * Combierte un XML a CSV
	 * @param  string $data   
	 * @param  string $path   
	 * @param  array  $config 
	 * @return \CsvTo\Create 
	 * @throws \InvalidArgumentException if $data is not a valid XML
	 */
	function xml_to_csv(string $data, string $path, array $config = []) {
		if (!Xml::isXml($data)) {
			throw new \InvalidArgumentException("You must pass a valid XML");		
		}		
		$config['path'] = $path;
		return Create::start($data, $config);
	}
}
// JSON ==> CSV
if (!function_exists('json_to_csv')) {
	/**
	 * Combierte un JSON a CSV
	 * @param  string $data
	 * @param  string $path	
	 * @param  array  $config
	 * @return \CsvTo\Create 
	 * @throws \InvalidArgumentException if $data is not a valid Json string
	 */
	function json_to_csv(string $data, string $path, array $config = []) {
		if (!Json::isJson($data)) {
			throw new \InvalidArgumentException("You must pass a valid Json string");		
		}		
		$config['path'] = $path;
		return Create::start($data, $config);
	}
}


?>