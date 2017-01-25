<?php

namespace Bio\Alphabet;

interface AlphabetInterface
{
    public function name();
    public function validate($seq);
}