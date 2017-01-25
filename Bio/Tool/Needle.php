<?php

namespace Bio\Tool;

class NeedleException extends \Exception{}

class Needle extends Shell
{
	protected $score;
	protected $seqA;
	protected $seqB;

	public function __construct($seqA, $seqB)
	{
		$this->seqA = $seqA;
		$this->seqB = $seqB;
		parent::__construct("needle -asequence asis:{$seqA} -bsequence asis:{$seqB} -stdout -auto -gapopen 10 -gapextend 0.5");
	}

	public function exe()
	{
		$this->output = shell_exec($this->command);
		preg_match('/^# Score: (\d*\.\d*)/m',$this->output, $score);
        return $this->score = $score[1];
	}

}
