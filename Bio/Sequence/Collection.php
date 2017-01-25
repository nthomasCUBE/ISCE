<?php

namespace Bio\Sequence;

class CollectionException extends \Exception{}

class Collection
{
	protected $collection;

	public function __construct($collection = array())
	{
		$this->collection = $collection;
	}

	public function add(Record $rec)
	{
		array_push($this->collection, $rec); 
	}

	public function all()
	{
		return $this->collection;
	}
}
