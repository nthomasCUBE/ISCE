<?php

use Bio\Sequence\Record;
use Bio\Sequence\RecordException;

class RecordTest extends \PHPUnit_Framework_TestCase
{

    public function testRecordContructor()
    {
        $seqA = new Record();
        $this->assertEquals($seqA->seq(), "");
        $this->assertEquals($seqA->alphabet(), "DNA");
        $this->assertEquals($seqA->id(), "");
        $this->assertEquals($seqA->attributes(), array());

        $seqA = new Record("Name", "ACDEFGHIKLMNPQRST", "AA");
        $this->assertEquals($seqA->seq(), "ACDEFGHIKLMNPQRST");
        $this->assertEquals($seqA->alphabet(), "AA");
        $this->assertEquals($seqA->id(), "Name");
        $this->assertEquals($seqA->attributes(), array());

        $seqA = new Record("", "ACDEFGHIKLMNPQRST", "AA");
        $this->assertEquals($seqA->seq(), "ACDEFGHIKLMNPQRST");
        $this->assertEquals($seqA->alphabet(), "AA");
        $this->assertEquals($seqA->id(), "");
        $this->assertEquals($seqA->attributes(), array());

        $seqA = new Record("ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.:_-|+#", "ACDE", "AA", ['score' => 11]);
        $this->assertEquals($seqA->seq(), "ACDE");
        $this->assertEquals($seqA->alphabet(), "AA");
        $this->assertEquals($seqA->id(), "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.:_-|+#");
        $this->assertEquals($seqA->attributes(), ['score' => 11]);

        try {
            $seqA = new Record("ABCDEFG<HIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789.:_-|+#", "ATTCAAATAAAAG", "DNA");
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith("Record-Id contains unallowed characters", $e->getMessage());
            return;
        }
        $this->fail();

    }
}


