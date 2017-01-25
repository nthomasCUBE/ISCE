<?php

namespace Bio\Alphabet;

class RnaAlphabet extends Alphabet
{
	protected $name = 'RNA';
	protected $validate = true;
	protected $alphabet = array('A', 'C', 'G', 'U');
	protected $gap = array('.');
}