<?php

namespace Bio\Tool;

use Bio\IO\File\FastaFile;

class Filter
{
	protected $msa;
	protected $len;
	protected $dir;

	private function __construct(FastaFile $msa_a, FastaFile $msa_b, $dir)
	{
		$this->msa['a'] = $msa_a->data;
		$this->msa['b'] = $msa_b->data;
		$this->len['a'] = strlen($this->msa['a'][0]['seq']);
		$this->len['b'] = strlen($this->msa['b'][0]['seq']);
		$this->dir = $dir;

		Log::state('Original Input Data');
		Log::state('MSA A: ' . count($this->msa['a']) . 'x' . $this->len['a'] );
		Log::state('MSA B: ' . count($this->msa['b']) . 'x' . $this->len['b'] );

		$this->save($this->msa['a'], $this->dir .'/muscle.a.msa');
		$this->save($this->msa['b'], $this->dir .'/muscel.b.msa');
	}


	public static function create(FastaFile $msa_a, FastaFile $msa_b, $dir)
	{
		Log::info("Filtering Muscle Results...");
		return new Filter($msa_a, $msa_b, $dir);
	}
	
	private function save($records, $file)
	{
		$fh = fopen($file, 'w');
		foreach ($records as $record) {
			fwrite($fh, '>' . $record['id'] . "\n" . $record['seq'] . "\n");
		}
	}

	public function rows($min_rows)
	{
		Log::info("Filtering rows");

		$bad_keys = array();

		foreach ($this->msa['a'] as $key => $rec) {
			$gaps = substr_count ( $rec['seq'] , '-' );
			if($gaps > ($this->len['a'] / 2)){
				array_push($bad_keys, $key);
			}
		}
		foreach ($this->msa['b'] as $key => $rec) {
			$gaps = substr_count ( $rec['seq'] , '-' );
			if($gaps > ($this->len['b'] / 2)){
				array_push($bad_keys, $key);
			}
		}

		$left_seqs = count($this->msa['a']) - count(array_unique($bad_keys));

		if ($left_seqs < $min_rows) {
			Log::state("..skipping (only {$left_seqs} left)");
			return $this;
		} 
		foreach(array_unique($bad_keys) as $to_remove){
			$this->msa['a'][$to_remove] = false;
			$this->msa['b'][$to_remove] = false;
		}
		$this->msa['a'] = array_values(array_filter($this->msa['a']));
		$this->msa['b'] = array_values(array_filter($this->msa['b']));

		Log::state('MSA A: ' . count($this->msa['a']) . 'x' . $this->len['a'] );
		Log::state('MSA B: ' . count($this->msa['b']) . 'x' . $this->len['b'] );

		return $this;
	}

	public function columns()
	{
		Log::info("Filtering columns");
		$index_a = $this->filter_columns($this->msa['a']);
		$index_b = $this->filter_columns($this->msa['b']);

		$len_a = $this->write_index($index_a, $this->dir .'/index.a.msa');
		$len_b = $this->write_index($index_b, $this->dir .'/index.b.msa');

		$this->wirte_filtered($index_a, $this->msa['a'], $this->dir . '/filter.a.msa');
		$this->wirte_filtered($index_b, $this->msa['b'], $this->dir . '/filter.b.msa');

		Log::state('MSA A: ' . count($this->msa['a']) . 'x' . $len_a );
		Log::state('MSA B: ' . count($this->msa['b']) . 'x' . $len_b );

	}

	private function filter_columns($msa)
	{
		$sequences = array_map(function ($v) { return $v['seq'];}, $msa);
		$len = strlen($sequences[0]);
		$count = count($sequences);
		$tracker = array();

		for ($k=0; $k < $len; $k++) {

			$column = array_map(function($v) use ($k) { return substr($v,$k,1);}, $sequences);

			if(in_array("-", $column)) {
				$gaps = count(array_filter($column, function($v){ return ($v == '-') ? true : false;}));

				if($gaps > ($count / 2)){
					$tracker[$k] = '-';
					Log::state($k . "\t" . implode(',', $column) . "\t-", true);
					continue;
				}
				if(count(array_count_values($column)) === 2){
					$tracker[$k] = '-!';
					Log::state($k . "\t" . implode(',', $column) . "\t-!", true);
					continue;
				}
			}
			elseif(count(array_count_values($column)) === 1) {
				$tracker[$k] = '!';
				Log::state($k . "\t" . implode(',', $column) . "\t!", true);
				continue;
			}
			Log::state($k . "\t" . implode(',', $column) . "\t+", true);
			$tracker[$k] = '+';
		}
		return $tracker;
	}

	private function write_index($index, $file)
	{
		$count = 0;
		$fh = fopen($file, 'w');
		foreach ($index as $key => $symbol) {
			fwrite($fh, $key ."\t". $symbol ."\n");
			if ($symbol == '+') {
				$count++;
			}
		}
		fclose($fh);
		return $count;
	}

	private function wirte_filtered($index, $msa, $file)
	{
		$fh = fopen($file, 'w');

		foreach ($msa as $key => $record) {
			fwrite($fh, '>' . $record['id'] . "\n");
			foreach ($index as $k => $symbol) {
				if ($symbol == '+') {
					fwrite($fh, substr($record['seq'], $k, 1));
				}
			}
			fwrite($fh, "\n");
		}

		fclose($fh);
	}
}
