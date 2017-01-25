<?php

use Bio\Alphabet;
use Bio\Alphabet\AaAlphabet;
use Bio\Alphabet\DnaAlphabet;
use Bio\Alphabet\RnaAlphabet;

class AlphabetTest extends \PHPUnit_Framework_TestCase
{


    public function testDnaAlphabetConstruct()
    {
        $dna = new DnaAlphabet();
        $this->assertEquals($dna->name(), "DNA");
    }

    public function testDnaAlphabetValidation()
    {
        $dna = new DnaAlphabet();
        $data = "ATCGGCAT";
        $this->assertEquals($dna->validate($data), true);
        $data = "";
        $this->assertEquals($dna->validate($data), true);
        $data = "A.TCG.GCA.T";
        $this->assertEquals($dna->validate($data), true);
        $data = ".";
        $this->assertEquals($dna->validate($data), true);

        $data = NULL;
        $this->assertEquals($dna->validate($data), false);
        $data = " ";
        $this->assertEquals($dna->validate($data), false);
        $data = " ATG";
        $this->assertEquals($dna->validate($data), false);
        $data = "ATG ";
        $this->assertEquals($dna->validate($data), false);
        $data = "AT G";
        $this->assertEquals($dna->validate($data), false);
        $data = false;
        $this->assertEquals($dna->validate($data), false);
        $data = true;
        $this->assertEquals($dna->validate($data), false);
        $data = "AUGC";
        $this->assertEquals($dna->validate($data), false);
        $data = 1;
        $this->assertEquals($dna->validate($data), false);
        $data = 0;
        $this->assertEquals($dna->validate($data), false);
        $data = "ATGC\nATGC";
        $this->assertEquals($dna->validate($data), false);
        $data = "ATGCaATGC";
        $this->assertEquals($dna->validate($data), false);
    }

    public function testRnaAlphabetConstruct()
    {
        $dna = new RnaAlphabet();
        $this->assertEquals($dna->name(), "RNA");
    }

    public function testRnaAlphabetValidation()
    {
        $dna = new RnaAlphabet();
        $data = "AUCGGCAU";
        $this->assertEquals($dna->validate($data), true);
        $data = "";
        $this->assertEquals($dna->validate($data), true);
        $data = "A.UCG.GCA.U";
        $this->assertEquals($dna->validate($data), true);
        $data = ".";
        $this->assertEquals($dna->validate($data), true);

        $data = NULL;
        $this->assertEquals($dna->validate($data), false);
        $data = " ";
        $this->assertEquals($dna->validate($data), false);
        $data = " AUG";
        $this->assertEquals($dna->validate($data), false);
        $data = "AUG ";
        $this->assertEquals($dna->validate($data), false);
        $data = "AU G";
        $this->assertEquals($dna->validate($data), false);
        $data = false;
        $this->assertEquals($dna->validate($data), false);
        $data = true;
        $this->assertEquals($dna->validate($data), false);
        $data = "ATUGC";
        $this->assertEquals($dna->validate($data), false);
        $data = 1;
        $this->assertEquals($dna->validate($data), false);
        $data = 0;
        $this->assertEquals($dna->validate($data), false);
        $data = "AUGC\nAUGC";
        $this->assertEquals($dna->validate($data), false);
        $data = "AUGCuAUGC";
        $this->assertEquals($dna->validate($data), false);
    }

    public function testAaAlphabetConstruct()
    {
        $dna = new AaAlphabet();
        $this->assertEquals($dna->name(), "AA");
    }

    public function testAaAlphabetValidation()
    {
        $dna = new AaAlphabet();
        $data = "ACDEFGHIKLMNPQRSTVWYACDEFGHIKLMNPQRSTVWY";
        $this->assertEquals($dna->validate($data), true);
        $data = "";
        $this->assertEquals($dna->validate($data), true);
        $data = ".AC..DEFGHIKL..MNPQRSTVWY.";
        $this->assertEquals($dna->validate($data), true);
        $data = ".";
        $this->assertEquals($dna->validate($data), true);

        $data = NULL;
        $this->assertEquals($dna->validate($data), false);
        $data = " ";
        $this->assertEquals($dna->validate($data), false);
        $data = " PQR";
        $this->assertEquals($dna->validate($data), false);
        $data = "PQR ";
        $this->assertEquals($dna->validate($data), false);
        $data = "PQ R";
        $this->assertEquals($dna->validate($data), false);
        $data = false;
        $this->assertEquals($dna->validate($data), false);
        $data = true;
        $this->assertEquals($dna->validate($data), false);
        $data = "YACBDEF";
        $this->assertEquals($dna->validate($data), false);
        $data = 1;
        $this->assertEquals($dna->validate($data), false);
        $data = 0;
        $this->assertEquals($dna->validate($data), false);
        $data = "YACDEF\nYACDEF";
        $this->assertEquals($dna->validate($data), false);
        $data = "YACDEFvACDEF";
        $this->assertEquals($dna->validate($data), false);
    }
}