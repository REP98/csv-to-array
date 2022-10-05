<?php 
declare(strict_types=1);
namespace CsvTo;

use CsvTo\Load;
use DomDocument;
/**
 * Combierte CSV a XML
 */
class Xml
{
	/**
	 * Nombre de archivo a exportar
	 * @var string
	 */
	protected string $fileName = "File.xml";
	/**
	 * Cargador del CSV
	 * @var \CsvTo\Load
	 */
	protected ?Load $load;
	/**
	 * @var \DomDocument
	 */
	protected ?DomDocument $doc;
	/**
	 * Indica el directorio de exportación o false si no se desea exportar
	 * @var boolean|string
	 */
	protected bool|string $export = false;
	/**
	 * Indica si se debe usar las cabeceras
	 * @var boolean
	 */
	protected bool $setHeaderXml = false;
	/**
	 * Codificación del XML
	 * @var string
	 */
	protected string $encoding = 'UTF-8';
	/**
	 * Constructor de la clase Xml
	 * @param string $file   Ruta del Archivo CSV
	 * @param array  $config Configuraciones
	 */
	function __construct(string $file, array $config = [])
	{
		if (array_key_exists('export', $config)) {
			$export = substr($config['export'], -1) == DIRECTORY_SEPARATOR ? $config['export'] : $config['export'].DIRECTORY_SEPARATOR;
			$this->export = $export;
			unset($config['export']);
		}

		if (array_key_exists('encoding', $config)) {
			$this->encoding = $config['encoding'];
			unset($config['encoding']);
		}

		if (array_key_exists('setHeaderXml', $config)) {
			$this->setHeaderXml = $config['setHeaderXml'];
			unset($config['setHeaderXml']);
		}

		$f = explode(DIRECTORY_SEPARATOR, $file);

		$this->fileName = str_ireplace('.csv', '.xml', $f[count($f) - 1]);

		$this->load = Load::file($file, $config);

		$this->doc = new DomDocument('1.0', $this->encoding);
		$this->doc->formatOutput = true;
	}
	/**
	 * Genera el XML
	 * @param  string      $root Etiqueta padre, por defecto <csv/>
	 * @param  string      $line Etiqueta de cada linea, por defecto <lines/>
	 * @param  string|null $data Etiqueta Global de datos, por defecto null
	 * @return string            El XML generado 
	 */
	public function generate(string $root = 'csv', string $line = "lines", string $data = null): string
	{
		$tagCsv = $this->doc->createElement($root);
		$tagRoot = $this->doc->appendChild($tagCsv);
		$e = 1;
		foreach ($this->load->getLines() as $l => $content) {
			$container = $this->doc->createElement($line);

			foreach ($content as $name => $value) {
				if (empty($name)) {
					//@codeCoverageIgnoreStart
					continue;
					//@codeCoverageIgnoreEnd
				}
				$tag = $this->escapeTag(is_null($data) ? $name : $data . $e);
				$e++;
				$child = $this->doc->createElement($tag);
				$child = $container->appendChild($child);
				$val = $this->doc->createTextNode($value);
				$val = $child->appendChild($val);
			}

			$tagRoot->appendChild($container);
		}
		$stringXml = $this->doc->saveXML();
		if ($this->export !== false && is_dir($this->export)) {
			file_put_contents($this->export.$this->fileName, $stringXml);
		}
		return $stringXml;
	}
	/**
	 * Escapa los texto para su conversion en etiqueta
	 * @param  string $tag
	 * @return string
	 */
	private function escapeTag(string $tag): string
	{
		$string = trim($tag);
		$string = str_replace(
	        [
	        	'á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä',
	        	'é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë',
	        	'í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î',
	        	'ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô',
	        	'ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü',
	        	'ñ', 'Ñ', 'ç', 'Ç'
	        ],
	        [
	        	'a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A',
	        	'e', 'e', 'e', 'e', 'E', 'E', 'E', 'E',
	        	'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I',
	        	'o', 'o', 'o', 'o', 'O', 'O', 'O', 'O',
	        	'u', 'u', 'u', 'u', 'U', 'U', 'U', 'U',
	        	'n', 'N', 'c', 'C'

	        ],
	        $string
	    );
	 
	    //Esta parte se encarga de eliminar cualquier caracter extraño
	    $string = str_replace(
	        [
	        	"\"", "¨", "º", "-", "~",
	             "#", "@", "|", "!", "\\",
	             "·", "$", "%", "&", "/",
	             "(", ")", "?", "'", "¡",
	             "¿", "[", "^", "<code>", "]",
	             "+", "}", "{", "¨", "´",
	             ">", "< ", ";", ",", ":",
	             ".", " ", " "
	        ],
	        '',
	        $string
	    );
		return $string;
	}
	/**
	 * Establece las cabeceras si $setHeaderXml es igual a true
	 * @return void
	 */
	public function headers(): void
	{
		if ($this->setHeaderXml == true) {
			header( "content-type: application/xml; charset=".$this->encoding );
		}
	}
	/**
	 * Metodo estatico de Xml::__construct(string $file, array $config = []) y retorna un Json
	 * @param  string $file
	 * @param  array  $config
	 * @return string
	 */
	public static function file(string $file, array $config = [])
	{
		$xml = new static($file, $config);
		$xml->headers();
		return $xml->generate();
	}
	/**
	 * Valida si es un XML
	 * @param  string  $file
	 * @return boolean 
	 */
	public static function isXml($file): bool
	{
		return substr($file, 0, 5) == "<?xml";
	}
	/**
	 * Combierte un XML en Array
	 * @param  string $xml
	 * @return array
	 */
	public static function toArray($xml)
	{
		return json_decode(static::toJson($xml), true);
	}
	/**
	 * Retorna un XML en JSON
	 * @param  string $jsonXml
	 * @return string  
	 */
	public static function toJson($jsonXml)
	{
		$x = simplexml_load_string($jsonXml);
		return json_encode($x);
	}
}
?>