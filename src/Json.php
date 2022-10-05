<?php 
declare(strict_types=1);
namespace CsvTo;

use CsvTo\Load;
/**
 * Combierte CSV a un JSON
 */
class Json
{
	/**
	 * Indica si se establece el content-type a aplication/json
	 * @var boolean
	 */
	protected bool $setHeaderJson = false;
	/**
	 * Contructor de la clase \CsvTo\Json
	 * @param string $file   Nombre del archivo CSV
	 * @param array  $config Configuraciones
	 * @return \CsvTo\Json
	 */
	public function __construct(string $file, array $config = [])
	{
		if (array_key_exists('setHeaderJson', $config)) {
			$this->setHeaderJson = $config['setHeaderJson'];
			unset($config['setHeaderJson']);
		}
		$this->csv = Load::file($file, $config);
	}
	/**
	 * Retorna el Json obtenido del Csv
	 * @param  int|integer $line indice de la linea o -1 para obtener todas
	 * @return string
	 */
	public function getData(int $line = -1): string
	{
		$data = [
			"header" => $this->csv->getHeaders()
		];
		if ($line == -1) {
			$data['line'] = $this->csv->getLines();
		} else {
			$data['lines'] = $this->csv->getLine($line);
		}
		return json_encode($data);
	}
	/**
	 * Si `$setHeaderJson` es `true` se establece las cabeceras para retornar una respuesta json 
	 * @return void
	 */
	public function headers()
	{
		if ($this->setHeaderJson === true) {
			header('Content-Type: application/json; charset=utf-8');
			header('Access-Control-Allow-Origin: *');
		}		
	}
	/**
	 * Metodo estatico de Json::__construct(string $file, array $config = []) y retorna un Json
	 * @param  string $file
	 * @param  array  $config
	 * @return string
	 */
	public static function file(string $file, array $config = []): string
	{
		$json = new static($file, $config);
		$json->headers();
		return $json->getData();
	}
	/**
	 * Valida si es un JSON
	 * @param  string  $jsonString
	 * @return boolean 
	 */
	public static function isJson($jsonString): bool
	{
		return is_string($jsonString) && is_array(static::toArray($jsonString)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
	}
	/**
	 * Convierte un Json a Array
	 * @param  strng $jsonString
	 * @return array 
	 */
	public static function toArray($jsonString): ?Array
	{
		return json_decode($jsonString, true);
	}
}
?>