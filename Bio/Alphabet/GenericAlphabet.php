<?php

namespace Bio\Alphabet;

/**
 * GenericAlphabet Class
 *
 * has no validation or constrains regarding the characters of a sequence
 * use this for unknown/untrustable input file sources, as special gap symbols
 */
class GenericAlphabet extends Alphabet
{
	protected $name = 'Generic';
	protected $validate = false;
	protected $alphabet = [];
	protected $gap = [];
}
