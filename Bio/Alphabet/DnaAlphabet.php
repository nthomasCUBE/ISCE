<?php

namespace Bio\Alphabet;

class DnaAlphabet extends Alphabet
{
	protected $name = 'DNA';
	protected $validate = true;
	protected $alphabet = array('A', 'C', 'G', 'T');
	protected $gap = array('.');
}