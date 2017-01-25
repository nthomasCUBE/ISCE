<?php

namespace Bio\Alphabet;

class AaAlphabet extends Alphabet
{
	protected $name = 'AA';
	protected $validate = true;
	protected $alphabet = array('A', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'Y');
	protected $gap = array('.');
}
