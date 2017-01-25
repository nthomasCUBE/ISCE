<?php

namespace Bio\Tool;

class ShellException extends \Exception{}

class Shell
{
	protected $command;
	protected $output;

	public function __construct($command = NULL)
	{
		$this->command = $command;
	}

	public function exe()
	{
		return $this->output = shell_exec($this->command); 
	}

}
