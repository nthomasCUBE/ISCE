<?php

namespace Bio\IO\File;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class FileException extends \Exception{}

class File
{
	protected $fs;
	protected $filename;
	protected $data;

	public function __construct($filename = NULL)
	{
		$this->fs = new Filesystem;
		$this->setFilename($filename);
	}

	public function toArray()
	{
		$this->checkReadable();
		return file($this->filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}

	public function toString()
	{
		$this->checkReadable();
		return file_get_contents($this->filename);
	}

	public function setFilename($filename)
	{
		if (!trim($filename) || $filename === true) {
			throw new FileException("Given filename is not allowed");
		}
		$this->filename = trim($filename);
	}

	public function checkReadable()
	{
		if (!is_readable($this->filename)) {
			throw new FileException("File doesn't exist or is read protected");
		}
	}

	public function name()
	{
		return $this->filename;
	}

}