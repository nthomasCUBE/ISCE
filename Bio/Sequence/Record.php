<?php

namespace Bio\Sequence;

class RecordException extends \Exception{}

class Record extends Seq
{
	protected $id;
	protected $attributes;
	private $allowedIdCharecters = array(	'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
											'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
											'0','1','2','3','4','5','6','7','8','9','.',':','_','-','|','+','#'
										);

	public function __construct($id = NULL, $seq = NULL, $alphabet = "DNA", $attributes = array())
	{		
		$this->setId($id);
		$this->attributes = $attributes;
		parent::__construct($seq, $alphabet);

	}

	public function setId($id)
	{
		$id = trim($id);
		if (!$id) {
			$this->id = "";
			return;
		}

		foreach (str_split($id) as $element) {
			if (!in_array($element, $this->allowedIdCharecters)) {
				throw new RecordException("Record-Id contains unallowed characters");
			}
		}
		$this->id = $id;
	}

	public function id()
	{
		return $this->id;
	}

	public function attributes()
	{
		return $this->attributes;
	}

}