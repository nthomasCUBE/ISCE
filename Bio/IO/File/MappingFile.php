<?php

namespace Bio\IO\File;

use Bio\Seq\Collection;

class MappingException extends \Exception{}

/**
 * The MappingFile Class
 * reads a mapping file in the format:
 * A1 	B1
 * A2	B2
 * A3	B4
 * A4	B3
 */
class MappingFile extends File
{
	protected $data = [];
	protected $size = [];

	public function __construct($filename = NULL, $seperator = "\t")
	{
		parent::__construct($filename);
		$this->parse($seperator);
	}

	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	protected function parse($seperator)
	{
		$content = $this->toArray();

		if (empty($content)) {
			$this->data = [];
			$this->size = 0;
		}

		foreach ($content as $index => $line) {
			$extracted = $this->extract($line, $seperator);
			if (sizeof($extracted) !== 2) {
				throw new MappingException("Parsing Error in data line: " . $extracted[0] . " " . ($index + 1));
			}
			array_push($this->data, $extracted);
		}
	}

	private function extract($data, $seperator)
	{
		$data = explode($seperator, $data);
		return $data;
	}

	# this function checks the pre-filter size
	# should be corrected in the final version
	public function mapGroups($groupA, $groupB, $minMatches){

		$grpA = array();
		$grpB = array();
        foreach ($this->data as $map) {
            if (array_key_exists($map[0], $groupA) && array_key_exists($map[1], $groupB)) {
                $grpA[$map[0]] = $groupA[$map[0]];
                $grpB[$map[1]] = $groupB[$map[1]];
            }
        }
        if (sizeof($grpA) < $minMatches) {
            throw new MappingException("Not enough mapping matches to calculate anything, found: " . sizeof($grpA) );
        }
        $this->A = $grpA;
        $this->B = $grpB;
	}
}