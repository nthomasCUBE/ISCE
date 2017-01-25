<?php

namespace Bio\IO\File;

use Bio\Tool\Log;

class OrthoFinderFileException extends \Exception{}


class OrthoFinderFile extends CsvTableFile
{
	public static function create($file)
	{
		Log::info("Reading OrtoFinder Index File: " . $file);
		return new OrthoFinderFile($file);
	}

	public function getLocus($locus_id){
		$results = $this->findRowByElement($locus_id);
		$results = @array_pop($results);
		$results = array_filter($results, function($val) { return ($val[0] === '') ? false : true; });
		return $results;
	}

	public function getGroupId($locus_id){

		foreach ($this->data as $row_id => $line) {
			foreach ($line as $col_id => $cell) {
				if (in_array($locus_id, $cell)) {
					return $this->rows[$row_id];
				}
			}
		}
		throw new OrthoFinderFileException("Locus Id not found");
	}
}