<?php 
declare(strict_types=1);
namespace CsvTo;

/**
 * Load
 * Carga los archivos CSV
 */
class Load
{
	/**
	 * Nombre del archivo a cargar
	 * @var string
	 */
	private string $fileName = '';
	/**
	 * Configuración para la obtención de las filas del CSV
	 * @var array
	 */
	protected array $config = [
		"separator" => ",",
		"enclosure" => "\"",
		"escape" => "\\",
		"withoutHeader" => false
	];
	/**
	 * Contiene todas las cabeceras o la primera linea del csv
	 * @var array
	 */
	protected array $headers = [];
	/**
	 * Contiene el resto de las lineas del CSV
	 * @var array
	 */
	protected array $lines = [];
	/**
	 * Carga el Archivo CSV y las configuraciones para su conversión
	 * La configuraciones admitidas son:
	 * * separator  Establece el delimitador de campos del CSV, por defecto se usa la coma `,`
	 * * enclosure  Establece el carácter circundante de cada campo, por defecto se usa las comillas doble `"`
	 * * escape		Establece el carácter de escape, por defecto se usa la barra invertida `\`
	 * * withoutHeader Establece si el CSV no tiene cabecera, por defecto es `false`
	 * @param string $files  Ruta con nombre del archivo csv
	 * @param array  $config Configuraciones para su cargado
	 * @return \CsvTo\Load|self
	 */
	protected function __construct(string $files, array $config = [])
	{
		$this->fileName = $files;
		if (!empty($config)) {
			$this->config = array_merge(
				$this->config,
				$config
			);
		}
		$csv = array_map(fn ($v) => str_getcsv($v, $this->config['separator'], $this->config['enclosure'], $this->config['escape'] ), file($files) );

		array_walk($csv, function(&$a) use ($csv) {
		  $a = array_combine($csv[0], $a);
		});

		if ($this->config['withoutHeader'] === false) {
			$this->headers = array_keys(array_shift($csv));
		}
		$this->lines = $csv;
	}
	/**
	 * Obtiene las cabeceras
	 * @return array
	 */
	public function getHeaders(): array
	{
		return $this->headers;
	}
	/**
	 * Obtiene las Lineas del CSV
	 * @return array
	 */
	public function getLines(): array
	{
		return $this->lines;
	}
	/**
	 * Obtiene la linea especificada
	 * @param  int $line Linea a obtener
	 * @return array|null       si la linea existe retorna su contenido de lo contrario retorna nulo
	 */
	public function getLine(int $line): ?array
	{
		return array_key_exists($line, $this->lines) ? $this->lines[$line] : null;
	}
	/**
	 * Establece las configuraciones y retorna una nueva instancia
	 * @param array $config matris de configuraciones
	 * @return \CsvTo\Load|self
	 */
	public function setConfig(array $config): Load
	{
		return new static($this->fileName, $config);
	}
	/**
	 * Método estático de Load::__construct()
	 * @param string $fileName  Ruta con nombre del archivo CSV
	 * @param array  $config Configuraciones para su cargado
	 * @return \CsvTo\Load|self
	 */
	public static function file(string $fileName, array $config = []): Load
	{
		return new static($fileName, $config);
	}

}
?>