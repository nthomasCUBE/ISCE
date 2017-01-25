<?php

use Bio\Sequence\Seq;
use Bio\Sequence\SeqException;

class SeqTest extends \PHPUnit_Framework_TestCase
{

    public function testSeqConstruct()
    {
        $seqA = new Seq("ACTGAAAgAAA", "dNA");
        $this->assertEquals($seqA->seq(), "ACTGAAAGAAA");
        $this->assertEquals($seqA->alphabet(), "DNA");

        $seqA = new Seq();
        $this->assertEquals($seqA->seq(), NULL);
        $this->assertEquals("DNA", $seqA->alphabet());

        $seqA = new Seq("AaAACC.CCC.GGGA.AAA");
        $this->assertEquals($seqA->seq(), "AAAACC.CCC.GGGA.AAA");
        $this->assertEquals($seqA->alphabet(), "DNA");

        $seqA = new Seq("", "dnA");
        $this->assertEquals($seqA->seq(), "");
        $this->assertEquals($seqA->alphabet(), "DNA");

        $seqA = new Seq("AUAGCGAUAGCUACGAU", "RNA");
        $this->assertEquals($seqA->seq(), "AUAGCGAUAGCUACGAU");
        $this->assertEquals($seqA->alphabet(), "RNA");

        $seqA = new Seq("ACDeFGH", "aA");
        $this->assertEquals($seqA->seq(), "ACDEFGH");
        $this->assertEquals($seqA->alphabet(), "AA");

        $seqA = new Seq(" ACDEFGH ", "AA");
        $this->assertEquals($seqA->seq(), "ACDEFGH");
        $this->assertEquals($seqA->alphabet(), "AA");

        try {
            $seqA = new Seq("ACDEFGH", "Unknow123");
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith('UNKNOW123 is an unknown sequence-alphabet', $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConstuctAndValidateDNA()
    {
        $seqA = new Seq("ATTCATA.AAAG", "DnA");
        $this->assertEquals($seqA->seq(), "ATTCATA.AAAG");
        $this->assertEquals($seqA->alphabet(), "DNA");

        try {
            $seqA = new Seq("ATTCUTAAAAG", "DNA");
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith("The sequence contains unallowed characters", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConstructAndValidateRNA()
    {
        $seqA = new Seq("AUGCG.ACAUU", "RnA");
        $this->assertEquals($seqA->seq(), "AUGCG.ACAUU");
        $this->assertEquals($seqA->alphabet(), "RNA");

        $seqA = new Seq(" AUGCGACAUU ", "RNA");
        $this->assertEquals($seqA->seq(), "AUGCGACAUU");
        $this->assertEquals($seqA->alphabet(), "RNA");

        try {
            $seqA = new Seq("AU GCAUGC", "RNA");
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith("The sequence contains unallowed characters", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConstructAndValidateAA()
    {
        $seqA = new Seq("ACDEFGHIK.LMNPQRST", "aA");
        $this->assertEquals($seqA->seq(), "ACDEFGHIK.LMNPQRST");
        $this->assertEquals($seqA->alphabet(), "AA");

        $seqA = new Seq(" ACDEFGHIKLMNPQRST ", "AA");
        $this->assertEquals($seqA->seq(), "ACDEFGHIKLMNPQRST");
        $this->assertEquals($seqA->alphabet(), "AA");

        try {
            $seqA = new Seq("ATTCUTAAAAG", "AA");
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith("The sequence contains unallowed characters", $e->getMessage());
            return;
        }
        $this->fail();
    }


}