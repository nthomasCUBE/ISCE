<?php

namespace Bio\Sequence;

use Bio\Alphabet\Alphabet;
use Bio\Alphabet\DnaAlphabet;
use Bio\Alphabet\RnaAlphabet;
use Bio\Alphabet\AaAlphabet;

class SeqException extends \Exception{}

class Seq
{
	protected $seq;
	protected $alphabet;

	public function __construct($seq = NULL, $alphabet = "DNA")
	{
		$this->setAlphabet($alphabet);
		$this->setSeq($seq);
	}

	protected function setAlphabet($alphabet)
	{
		$alphabet = trim(strtoupper($alphabet));

		if ($alphabet == "DNA") {
			$this->alphabet = new DnaAlphabet;
		} 
		elseif ($alphabet == "RNA") {
			$this->alphabet = new RnaAlphabet;
		}
		elseif ($alphabet == "AA") {
			$this->alphabet = new AaAlphabet;
		}
		else {
			throw new SeqException($alphabet . " is an unknown sequence-alphabet");
		}
	}

	protected function setSeq($seq)
	{
		$seq = trim(strtoupper($seq));

		if (!$seq) {
			$this->seq = "";
			return;
		}

		if ($this->alphabet->validate($seq)) {
			$this->seq = $seq;
		}
		else {
			throw new SeqException("The sequence contains unallowed characters");
		}
	}

	public function seq()
	{
		return $this->seq;
	}

	public function append($seq)
	{
		$seq = trim(strtoupper($seq));

		if (!$seq) {
			return;
		}

		if ($this->alphabet->validate($seq)) {
			$this->seq .= $seq;
		}
		else {
			throw new SeqException("The sequence contains unallowed characters");
		}
	}

	public function alphabet()
	{
		return $this->alphabet->name();
	}
}