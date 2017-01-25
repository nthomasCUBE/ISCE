<?php

namespace Bio\IO\File;

class CsvTableException extends \Exception{}

/**
 * The CsvTableFile Class
 * reads a table with tab seperated columns 
 * and comma sperated values in each cell
 * the rows are seperated by "\n" or "\r\n" 
 * the headline is optional
 */
class CsvTableFile extends File
{

	protected $headline;
	protected $cols = [];
	protected $rows = [];
	protected $data = [[]];
	protected $table_width = 0;
	protected $table_height = 0;

	public function __construct($filename = NULL, $headline = true, $element_seperator = ',', $column_seperator = "\t")
	{
		parent::__construct($filename);
		$this->parse($headline, $column_seperator, $element_seperator);
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	protected function parse($headline, $column_seperator, $element_seperator)
	{
		$content = $this->toArray();

		if (empty($content)) {
			throw new CsvTableException("Input file doesn't contain a table structure");
		}

		if ($headline === true) {
			$this->headline = true;
			$extracted = $this->extract(array_shift($content), $column_seperator);
			if (array_shift($extracted) != '') {
				throw new CsvTableException("Headline has to start with a tab");
			}
			$this->cols = $extracted;
			$this->table_width = sizeof($this->cols);
		}
		else {
			$this->headline = false;
			$extracted = $this->extract($content[0], $column_seperator);
			if (sizeof($extracted) < 2) {
				throw new CsvTableException("Too few columns to be a table");
			}
			$this->table_width = sizeof($extracted) - 1;
			$this->cols = range(0, $this->table_width - 1);

		}
		foreach ($content as $index => $data) {
			$extracted = $this->extract($data, $column_seperator, $element_seperator);
			array_push($this->rows, array_shift($extracted)[0]);
			$this->data[$index] = array_values($extracted);
			$this->table_height ++;
			if (sizeof($this->data[$index]) !== $this->table_width) {
				throw new CsvTableException("Parsing Error in data line: " . ($index + 1));
			}
		}
	}

	private function extract($data, $outer_seperator, $inner_seperator = NULL)
	{

		$data = explode($outer_seperator, $data);

		if ($inner_seperator) {
			foreach ( $data as $pos => $dat) {
				$data[$pos] = array_map("trim", explode($inner_seperator, $dat));
			}
		}
		return $data;
	}

	public function findRow($search)
	{
		$results = [];
		$return = [];

		foreach ($this->rows as $index => $value) {
			if ($value === $search) {
				array_push($results, $index);
			}
		}
		foreach ($results as $index) {
			array_push($return, [$index => $this->data[$index] ] );
		}

		return $return;
	}

	public function findRowByElement($search)
	{
		$results = [];
		$return = [];

		foreach ($this->data as $row_id => $line) {
			foreach ($line as $col_id => $cell) {
				if (in_array($search, $cell)) {
					array_push($results, $row_id);
				}
			}
		}

		foreach ($results as $row_id) {
			$tmp;
			foreach ( $this->data[$row_id] as $key => $value ){
				$tmp[ $this->cols[$key] ] = $value;
			}
			$return[$row_id] = $tmp;
		}

		return $return;
	}
}