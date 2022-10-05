<?php 
declare(strict_types=1);
namespace CsvTo;


use ArrayAccess;
use CsvTo\Load;
/**
 * Convierte el CSV a un Array PHP
 * 
 * ```php
 * use CsvTo\Arr;
 *
 * $lines = Arr::files('myfile.csv');
 *
 * var_dump($lines[3]); // Imprime la tercera linea
 * ```
 */
class Arr extends Load implements ArrayAccess
{
	
	function __construct(string $file, array $config = [])
	{
		parent::__construct($file, $config);

	}
	/**
	 * Establece un parámetro al CSV
	 * @param  mixed  $offset
	 * @param  mixed  $value
	 * @return void
	 */
	public function offsetSet(mixed $offset, mixed $value): void 
	{
		if (is_null($offset)) {
			$this->lines[] = $value;
		} else {
			$this->lines[$offset] = $value;
		}
	}
	/**
	 * Verifica que un indice exista
	 * @param  mixed  $offset
	 * @return bool
	 */
	public function offsetExists(mixed $offset): bool
	{
        return isset($this->lines[$offset]);
    }
    /**
     * Elimina un indice
     * @param  mixed  $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->lines[$offset]);
    }
    /**
     * Obtiene una linea por su indice
     * @param  mixed  $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return isset($this->lines[$offset]) ? $this->lines[$offset] : null;
    }
}
?>