<?php

namespace Bio\Alphabet;

abstract class Alphabet implements AlphabetInterface
{
	
	public function name()
	{
		return $this->name;
	}

	public function validate($seq)
	{

		if( $this->validate === false) {
			return true;
		}

		if ( $seq === '') {
			return true;
		}
		
		foreach (str_split($seq) as $element) {
			if (!in_array($element, array_merge($this->alphabet, $this->gap))) {
				return false;
			}
		}
		
		return true;
	}
}