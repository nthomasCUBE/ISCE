<?php

namespace Bio\IO\File;

use Bio\Seq\Collection;

class FastaFileException extends \Exception{}

class FastaFile extends File
{

	protected $data;

	public function __construct($filename = NULL)
	{
		parent::__construct($filename);
		$this->parse();
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
	
	private function parse()
	{
		$content = $this->toArray();

		$index = -1;
		foreach ($content as $line) {
			if ($line[0] === ';') {
				continue;
			}
			elseif ($line[0] === '>') {
				$index++;
				$attributes = preg_split('/\s+/', trim(substr($line,1)));
				$this->data[$index]['id'] = array_shift($attributes);
				$this->data[$index]['attributes'] = $attributes;
				$this->data[$index]['seq'] = '';
			}
			else {
				$this->data[$index]['seq'] .= $line;
			}
		}
	}

	public function findSeqById($id) {
		foreach ($this->data as $index => $record) {
			if ($record['id'] === $id) {
				return $record['seq'];
			}
		}
	}

	public function seqLength($id = 0){
		if (isset($this->data[$id]['seq'])) {
			return strlen($this->data[$id]['seq']);
		}
		throw new FastaFileException("Record ID doesn't exist");		
	}

}