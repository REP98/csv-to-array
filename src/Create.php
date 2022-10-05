<?php 
declare(strict_types=1);
namespace CsvTo;

use CsvTo\Arr;
use CsvTo\Json;
use CsvTo\Xml;

/**
 * Crea archivos CSV 
 */
class Create
{	
	/**
	 * Indica si se debe mostrar la cabecera
	 * @var boolean
	 */
	protected bool $withoutHeader = false;
	/**
	 * Delimitador del CSV
	 * @var string
	 */
	protected string $delimiter = ',';
	/**
	 * Caracater Circundante de Cada Campo
	 * @var string
	 */
	protected string $enclosure = '"';
	/**
	 * Caracater de Escape
	 * @var string
	 */
	protected string $escape = "\\";
	/**
	 * Matriz de la cabezera
	 * @var array
	 */
	protected array $header = [];
	/**
	 * Matrix de Datos
	 * @var array
	 */
	protected array $data = [];
	/**
	 * Rutas a donde volcar el CSV
	 * @var string
	 */
	protected string $path;
	/**
	 * Incia la creacion del CSV
	 * @param array|string|mixed $data   La info, pude pasar un XML o Json es este parametro
	 * @param array|string $config Matriz de configuraciones, o cadena de texto de la ruta donde volcar el csv
	 * @return \CsvTo\Create
	 */
	function __construct($data, array|string $config)
	{
		if (is_string($config)) {
			$config = ["path" => $config];
		}
		$this->setConfig($config);
		$this->data = $this->getArrOfData($data);
		$this->header = $this->getHeaderOfData($this->data);		
		$this->Csv();
	}
	/**
	 * Metodo estatico alias \CsvTo\Create::__construct($data, array|string $config)
	 * @param  mixed $data 
	 * @param  string $config
	 * @return \CsvTo\Create
	 */
	public static function start($data, array|string $config): \CsvTo\Create
	{
		return new static($data, $config);
	}
	/**
	 * Obtiene la cabecera del la data
	 * @param  array  $arr
	 * @return array
	 */
	public function getHeaderOfData(array $arr): array
	{
		if ($this->withoutHeader === false) {
			if (count($arr) >= 1) {
				$header = array_keys($arr[0]);
				return $header;
			}
		}
		return [];
	}
	/**
	 * Obtiene el array de los datos
	 * @param  array|string $data
	 * @return array  
	 */
	public function getArrOfData($data): array
	{
		if (is_string($data)) {
			if (Xml::isXml($data)) {
				return Xml::toArray($data)['lines'];
			}
			if (Json::isJson($data)) {
				return Json::toArray($data)['line'];
			}
		}

		return is_array($data) ? $data : [$data];
	}
	/**
	 * Crea un CSV
	 * @return void
	 */
	public function Csv(): void
	{

		$csv = fopen($this->path, 'w');
		if ($this->withoutHeader === false) {
			fputcsv(
				$csv, 
				$this->header, 
				$this->delimiter,
				$this->enclosure,
				$this->escape,
			);
		}

		foreach ($this->data as $key => $value) {
			fputcsv(
				$csv, 
				$value, 
				$this->delimiter,
				$this->enclosure,
				$this->escape,
			);		
		}
		fclose($csv);
	}
	/**
	 * Establece las configuraciones actuales
	 * @param array $config
	 * @return void
	 */
	private function setConfig(array $config): void
	{
		$property = ['withoutHeader', 'delimiter', 'enclosure', 'path', 'escape'];

		if (is_array($config)) {
			foreach ($config as $key => $value) {
				if (in_array($key, $property)) {
					$this->{$key} = $value;
				}
			}
		}
	}
	/**
	 * Establece si se usa el header o no, ademas obtiene los valores de la cabecera
	 * @param bool $value
	 * @return bool 	Retorna el valor actual de $withoutHeader que deberiaser igual al pasado.
	 */
	public function setWithoutHeader(bool $value): bool
	{
		$this->withoutHeader = $value;
		$this->header = $this->getHeaderOfData($this->data);
		return $this->withoutHeader;
	}
}
?>