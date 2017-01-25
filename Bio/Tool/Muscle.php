<?php

namespace Bio\Tool;

use Bio\IO\File\FastaFile;

class MuscleException extends \Exception{}

class Muscle
{

	public static function make($seqs)
	{
		$tmp_input = tempnam(sys_get_temp_dir(), "isce");
		$tmp_output = tempnam(sys_get_temp_dir(), "isce");
		$fh_in = fopen($tmp_input, "w");

		foreach ($seqs as $seq) {
            fwrite($fh_in, ">" . $seq['fasta'] . "__" . $seq['id'] . "\n" . $seq['seq'] . "\n");
        }
        
        fclose($fh_in);
		
		$output = shell_exec("muscle -in {$tmp_input} -out {$tmp_output} -quiet");
		$content = new FastaFile($tmp_output);
		unlink($tmp_input);
        unlink($tmp_output);

        return $content;
        
	}
}


        