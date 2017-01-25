<?php

namespace Bio\Tool;

class AlgorithsException extends \Exception{}

class Algorithm
{

	private function index($filename, $id)
	{
		$file = file($this->dir.$filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		$input = array_map(function($v){return explode("\t",$v)[1];}, $file );
		$runner = 1;
		foreach ($input as $index => $symbol) {
			if ($symbol == "+") {
				$this->map[$id][$runner] = $index + 1;
				$runner++;
			}
		}
	}

	public function all($msa_a, $msa_b, $map, $result_dir)
	{
		$this->dir = $result_dir;
		$this->index('/msa/index.a.msa', 'a');
		$this->index('/msa/index.b.msa', 'b');

		Log::info("Running Algorithms");
		$filename_AB = $result_dir . '/msa/mapped.filter.ab.msa';
		$coev_dir = $result_dir . '/algorithms/';

		$len_a = $msa_a->seqLength();

		# Wirte the mapped MSAs 
        $fh = fopen($filename_AB, "w");
        Log::state("Mapping Correct MSA.");
        foreach ($map->data as $map) {
        	foreach ($msa_a->data as $key1 => $entryA) {
        		if(explode(".",$entryA['id'])[0] === explode(".",$map[0])[0]) {
        			foreach ($msa_b->data as $key2 => $entryB) {
        				if (explode(".",$entryB['id'])[0] === explode(".",$map[1])[0]) {
        					fwrite($fh, $entryA['id'] . "__" . $entryB['id'] . "\t" . $entryA['seq'] . $entryB['seq'] . "\n");
                            break;
        				}
        			}
        			break;
        		}
        	}
        }
        fclose($fh);

        Log::state("Running Coev-Algorithms.");
		shell_exec("java covariance.algorithms.OmesCovariance " . 	$filename_AB . " " . $coev_dir . "/omes");
		shell_exec("java covariance.algorithms.McBASCCovariance " . $filename_AB . " " . $coev_dir . "/basc");
		shell_exec("java covariance.algorithms.MICovariance " . 	$filename_AB . " " . $coev_dir . "/mi");
		shell_exec("java covariance.algorithms.ELSCCovariance " . 	$filename_AB . " " . $coev_dir . "/elsc");

		Log::state("Cleaning Result Files.");
		$this->_clean($coev_dir . '/omes', 	$len_a);	//0.0
		$this->_clean($coev_dir . '/basc', 	$len_a);	//-2.0
		$this->_clean($coev_dir . '/mi', 	$len_a);	//0.0
		$this->_clean($coev_dir . '/elsc', 	$len_a);	//-0

	}

	public function all_fixed_size($msa_a, $msa_b, $map, $result_dir, $size)
	{
		$this->dir = $result_dir;
		$this->index('/msa/index.a.msa', 'a');
		$this->index('/msa/index.b.msa', 'b');

		Log::info("Running Algorithms");
		$filename_AB = $result_dir . '/msa/mapped.filter.ab.msa';
		$coev_dir = $result_dir . '/algorithms/';

		$len_a = $msa_a->seqLength();

		# Wirte the mapped MSAs 
        $fh = fopen($filename_AB, "w");
        $count = 0;
        Log::state("Mapping Correct MSA.");
        foreach ($map->data as $map) {
        	foreach ($msa_a->data as $key1 => $entryA) {
        		if(explode(".",$entryA['id'])[0] === explode(".",$map[0])[0]) {
        			foreach ($msa_b->data as $key2 => $entryB) {
        				if (explode(".",$entryB['id'])[0] === explode(".",$map[1])[0]) {
        					fwrite($fh, $entryA['id'] . "__" . $entryB['id'] . "\t" . $entryA['seq'] . $entryB['seq'] . "\n");
        					$count++;
                            break;
        				}
        			}
        			break;
        		}
        	}
        	if($count == $size){
        		break;
        	}
        }
        fclose($fh);

        Log::state("Running Coev-Algorithms.");
		shell_exec("java covariance.algorithms.OmesCovariance " . 	$filename_AB . " " . $coev_dir . "/omes");
		shell_exec("java covariance.algorithms.McBASCCovariance " . $filename_AB . " " . $coev_dir . "/basc");
		shell_exec("java covariance.algorithms.MICovariance " . 	$filename_AB . " " . $coev_dir . "/mi");
		shell_exec("java covariance.algorithms.ELSCCovariance " . 	$filename_AB . " " . $coev_dir . "/elsc");

		Log::state("Cleaning Result Files.");
		$this->_clean($coev_dir . '/omes', 	$len_a);	//0.0
		$this->_clean($coev_dir . '/basc', 	$len_a);	//-2.0
		$this->_clean($coev_dir . '/mi', 	$len_a);	//0.0
		$this->_clean($coev_dir . '/elsc', 	$len_a);	//-0

	}

	private function _clean($input, $len_a){
		
		$in = file($input, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		
		$out = fopen($input . '.clear', 'w');
		#$out_pc = fopen($input . '.clear_pc', 'w');
		$head = array_shift($in);

		fwrite($out, "i\tj\tc_i\tc_j\tscore\ttype\n"); # header
		#fwrite($out_pc, $head . "\ttype\n"); # header

		foreach ($in as $line) {
			$elements = explode("\t", $line);

			if (($elements[0] + 1) <= $len_a && ($elements[1] + 1) > $len_a) {
				$i = $elements[0] + 1;
				$j = $elements[1] + 1 - $len_a;
            	$type = "AB";
            	$ci = $this->map['a'][$i];
            	$cj = $this->map['b'][$j];
            	fwrite($out, $i . "\t" . $j . "\t" . $ci . "\t" . $cj . "\t" . $elements[2] . "\t" . $type . "\n");

            }
            #elseif (($elements[0] + 1) <= $len_a && ($elements[1] + 1) <= $len_a) {
			#	$i = $elements[0] + 1;
			#	$j = $elements[1] + 1;
            #	$type = "A";
            #	$ci = $this->map['a'][$i];
            #	$cj = $this->map['a'][$j];
            #}
            #elseif (($elements[0] + 1) > $len_a && ($elements[1] + 1) > $len_a) {
			#	$i = $elements[0] + 1 - $len_a;
			#	$j = $elements[1] + 1 - $len_a; 
            #	$type = "B";
            #	$ci = $this->map['b'][$i];
            #	$cj = $this->map['b'][$j];
            #}
            #else {
            #	echo "something went wrong in the AB/A/B classification";
            #	die();
            #}

			#fwrite($out, $i . "\t" . $j . "\t" . $ci . "\t" . $cj . "\t" . $elements[2] . "\t" . $type . "\n");		
		}
		fclose($out);

	}

}