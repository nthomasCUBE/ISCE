<?php

use ISCE\Config;
use ISCE\OrthoFinder;

class OrthoFinderTest extends \PHPUnit_Framework_TestCase
{
    protected $correct_config_1;

    protected function setUp()
    {
        $this->correct_config_1 = new Config(__DIR__ . '/../TestFiles/correct.config.1.json');
    }

    public function testIndexParserWithCorrectIndex()
    {
        $locus = 'A3';
        $index = $this->correct_config_1->get('path.speciesA.index');
        $group = OrthoFinder::getGroupByLocusId($locus, $index);
        $this->assertEquals($group['id'], 'OG0000001');
        $this->assertEquals(18, sizeof($group['members']));
        $this->assertEquals(4, sizeof($group['members']['A']));
        $this->assertEquals(1, sizeof($group['members']['K']));
    }

    public function testIndexParserWithTwoIdentLocusIDs()
    {
        try {
            $locus = 'A1';
            $index = $this->correct_config_1->get('path.speciesA.index');
            $group =OrthoFinder::getGroupByLocusId($locus, $index);
        } 
        catch (\Exception $e) {
            $this->assertStringEndsWith('found 2 groups for locus: A1', $e->getMessage());
            return;
        }
        $this->fail();
    }
}