<?php

use Bio\IO\File\CsvTableFile;
use Bio\IO\File\CsvTableFileException;

class CsvTableFileTest extends \PHPUnit_Framework_TestCase
{
    # csvTable01.txt 
    # ''
    public function test_1x1empty_asHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable01.txt';
            $fh = new CsvTableFile($fileA);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Input file doesn't contain a table structure", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable01.txt 
    # ''
    public function test_1x1empty_asNoHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable01.txt';
            $fh = new CsvTableFile($fileA, false);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Input file doesn't contain a table structure", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable02.txt 
    # 'A1   A2  A3  A4  A5  A6  A7'
    public function test_7x1data_wHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable02.txt';
            $fh = new CsvTableFile($fileA);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Headline has to start with a tab", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable02.txt 
    # 'A1   A2  A3  A4  A5  A6  A7'
    public function test_7x1data_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable02.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(6, $fh->table_width, "WIDTH");
        $this->assertEquals(1, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5], $fh->cols, "COLS");
        $this->assertEquals([0 => 'A1'], $fh->rows, "ROWS");
        $this->assertEquals([ 0 => [0 => ['A2'] , 1 => ['A3'], 2 => ['A4'], 3 => ['A5'], 4 => ['A6'], 5 => ['A7']]], $fh->data, "DATA");
    }

    # csvTable03.txt 
    # '    A1   A2  A3  A4  A5  A6  A7'
    public function test_8x1headline_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable03.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(0, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 'A1', 1 => 'A2' , 2 => 'A3', 3 => 'A4', 4 => 'A5', 5 => 'A6', 6 => 'A7'], $fh->cols, "COLS");
        $this->assertEquals([], $fh->rows, "ROWS");
        $this->assertEquals([[]], $fh->data, "DATA");
    }

    # csvTable03.txt 
    # '    A1   A2  A3  A4  A5  A6  A7'
    public function test_8x1headline_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable03.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(1, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 0, 1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6], $fh->cols, "COLS");
        $this->assertEquals([0 => ''], $fh->rows, "ROWS");
        $this->assertEquals([ 0 => [0 => ['A1'] , 1 => ['A2'], 2 => ['A3'], 3 => ['A4'], 4 => ['A5'], 5 => ['A6'], 6 => ['A7']]], $fh->data, "DATA");
    }

    # csvTable04.txt 
    # '    '
    public function test_2x1onetab_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable04.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(1, $fh->table_width, "WIDTH");
        $this->assertEquals(0, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => ''], $fh->cols, "COLS");
        $this->assertEquals([], $fh->rows, "ROWS");
        $this->assertEquals([[]], $fh->data, "DATA");
    }

    # csvTable04.txt 
    # '    '
    public function test_2x1onetab_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable04.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(1, $fh->table_width, "WIDTH");
        $this->assertEquals(1, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 0], $fh->cols, "COLS");
        $this->assertEquals([0 => ''], $fh->rows, "ROWS");
        $this->assertEquals([ 0 => [ 0 => ['']]], $fh->data, "DATA");
    }

    # csvTable05.txt 
    # 'A1'
    # 'A2'
    # 'A3'
    public function test_1x3_wHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable05.txt';
            $fh = new CsvTableFile($fileA);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Headline has to start with a tab", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable05.txt 
    # 'A1'
    # 'A2'
    # 'A3'
    public function test_1x3_woHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable05.txt';
            $fh = new CsvTableFile($fileA, false);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Too few columns to be a table", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable06.txt 
    # ' A1'
    # ' A2'
    # ' A3'
    public function test_2x3_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable06.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(1, $fh->table_width, "WIDTH");
        $this->assertEquals(2, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 'A1'], $fh->cols, "COLS");
        $this->assertEquals([0 => '', 1 => ''], $fh->rows, "ROWS");
        $this->assertEquals([ 0 => [0 => [ 0 => 'A2']], 1 => [0 => [0 => 'A3']]], $fh->data, "DATA");
    }

    # csvTable06.txt 
    # ' A1'
    # ' A2'
    # ' A3'
    public function test_2x3_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable06.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(1, $fh->table_width, "WIDTH");
        $this->assertEquals(3, $fh->table_height, "HEIGHT");
        $this->assertEquals([0 => 0], $fh->cols, "COLS");
        $this->assertEquals([0 => '', 1 => '', 2 => ''], $fh->rows, "ROWS");
        $this->assertEquals([0 => [0 => [0 => 'A1']], 1 => [0 => [0 => 'A2']], 2 => [0 => [0 => 'A3']]], $fh->data, "DATA");
    }

    # csvTable07.txt 
    public function test_8x4_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable07.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(3, $fh->table_height, "HEIGHT");
        $this->assertEquals(['A1','A2','A3','A4','A5','A6','A7'], $fh->cols, "COLS");
        $this->assertEquals(['R1', 'R2', 'R3'], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'C1a'], [0 =>'C1b'], [0 =>'C1c'], [0 =>'C1d'], [0 =>'C1e'], [0 =>'C1f'], [0 =>'C1g']]);
        $this->assertEquals($fh->data[1], [[0 => 'C2a'], [0 =>'C2b'], [0 =>'C2c'], [0 =>'C2d'], [0 =>'C2e'], [0 =>'C2f'], [0 =>'C2g']]);
        $this->assertEquals($fh->data[2], [[0 => 'C3a'], [0 =>'C3b'], [0 =>'C3c'], [0 =>'C3d'], [0 =>'C3e'], [0 =>'C3f'], [0 =>'C3g']]);
    }

    # csvTable07.txt 
    public function test_8x4_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable07.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(4, $fh->table_height, "HEIGHT");
        $this->assertEquals([0,1,2,3,4,5,6], $fh->cols, "COLS");
        $this->assertEquals(['','R1','R2','R3'], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'A1'], [0 =>'A2'], [0 =>'A3'], [0 =>'A4'], [0 =>'A5'], [0 =>'A6'], [0 =>'A7']]);
        $this->assertEquals($fh->data[1], [[0 => 'C1a'], [0 =>'C1b'], [0 =>'C1c'], [0 =>'C1d'], [0 =>'C1e'], [0 =>'C1f'], [0 =>'C1g']]);
        $this->assertEquals($fh->data[2], [[0 => 'C2a'], [0 =>'C2b'], [0 =>'C2c'], [0 =>'C2d'], [0 =>'C2e'], [0 =>'C2f'], [0 =>'C2g']]);
        $this->assertEquals($fh->data[3], [[0 => 'C3a'], [0 =>'C3b'], [0 =>'C3c'], [0 =>'C3d'], [0 =>'C3e'], [0 =>'C3f'], [0 =>'C3g']]);
    }

    # csvTable08.txt 
    public function test_8x4multielements_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable08.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(3, $fh->table_height, "HEIGHT");
        $this->assertEquals(['A1','A2','A3','A4','A5','A6','A7'], $fh->cols, "COLS");
        $this->assertEquals(['R1', 'R2', 'R3'], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'C1a', 1 => 'C1a1'], [0 =>'C1b'], [0 =>'C1c'], [0 =>'C1d'], [0 =>'C1e'], [0 =>'C1f'], [0 =>'C1g']]);
        $this->assertEquals($fh->data[1], [[0 => 'C2a'], [0 =>'C2b'], [0 =>''], [0 =>'C2d'], [0 =>'C2e'], [0 =>'C2f', 1 => 'C2f2'], [0 =>'C2g']]);
        $this->assertEquals($fh->data[2], [[0 => ''], [0 =>'C3b'], [0 =>'C3c'], [0 =>'C3d'], [0 =>'C3e'], [0 =>'C3f'], [0 =>'C3g']]);
    }

    # csvTable08.txt 
    public function test_8x4multielements_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable08.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(4, $fh->table_height, "HEIGHT");
        $this->assertEquals([0,1,2,3,4,5,6], $fh->cols, "COLS");
        $this->assertEquals(['','R1','R2','R3'], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'A1'], [0 =>'A2'], [0 =>'A3'], [0 =>'A4'], [0 =>'A5'], [0 =>'A6'], [0 =>'A7']]);
        $this->assertEquals($fh->data[1], [[0 => 'C1a', 1 => 'C1a1'], [0 =>'C1b'], [0 =>'C1c'], [0 =>'C1d'], [0 =>'C1e'], [0 =>'C1f'], [0 =>'C1g']]);
        $this->assertEquals($fh->data[2], [[0 => 'C2a'], [0 =>'C2b'], [0 =>''], [0 =>'C2d'], [0 =>'C2e'], [0 =>'C2f', 1 => 'C2f2'], [0 =>'C2g']]);
        $this->assertEquals($fh->data[3], [[0 => ''], [0 =>'C3b'], [0 =>'C3c'], [0 =>'C3d'], [0 =>'C3e'], [0 =>'C3f'], [0 =>'C3g']]);
    }

    # csvTable09.txt 
    public function test_8x3multielements_empty_wHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable09.txt';
            $fh = new CsvTableFile($fileA);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Headline has to start with a tab", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable09.txt 
    public function test_8x3multielements_empty_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable09.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(7, $fh->table_width, "WIDTH");
        $this->assertEquals(3, $fh->table_height, "HEIGHT");
        $this->assertEquals([0,1,2,3,4,5,6], $fh->cols, "COLS");
        $this->assertEquals(['R1','R2','R3'], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'C1a', 1 => 'C1a1'], [0 =>'C1b'], [0 =>'C1c'], [0 =>'C1d'], [0 =>'C1e'], [0 =>'C1f'], [0 =>'C1g']], "DATA1");
        $this->assertEquals($fh->data[1], [[0 => 'C2a'], [0 =>'C2b'], [0 =>''], [0 =>'C2d'], [0 =>'C2e'], [0 =>'C2f', 1 => 'C2f2'], [0 =>'C2g']]);
        $this->assertEquals($fh->data[2], [[0 => ''], [0 =>'C3b'], [0 =>'C3c'], [0 =>'C3d'], [0 =>'C3e'], [0 =>'C3f'], [0 =>'C3g']]);
    }

    # csvTable10.txt 
    public function test_8x3toomanytabs_wHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable10.txt';
            $fh = new CsvTableFile($fileA);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Headline has to start with a tab", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable10.txt 
    public function test_8x3toomanytabs_woHeadline()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/csvTable10.txt';
            $fh = new CsvTableFile($fileA, false);
        }
        catch (\Exception $e) {
             $this->assertStringStartsWith("Parsing Error in data line: 2", $e->getMessage());
             return;
        }
        $this->fail();
    }

    # csvTable11.txt 
    public function test_3x3emptyindexes_wHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable11.txt';
        $fh = new CsvTableFile($fileA);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(2, $fh->table_width, "WIDTH");
        $this->assertEquals(2, $fh->table_height, "HEIGHT");
        $this->assertEquals(['',''], $fh->cols, "COLS");
        $this->assertEquals(['',''], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => 'A1'], [0 =>'A2']]);
        $this->assertEquals($fh->data[1], [[0 => 'B1'], [0 =>'B2']]);
    }

    # csvTable11.txt 
    public function test_3x3emptyindexes_woHeadline()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/csvTable11.txt';
        $fh = new CsvTableFile($fileA, false);
        $this->assertEquals($fileA, $fh->name(), "NAME");
        $this->assertEquals(2, $fh->table_width, "WIDTH");
        $this->assertEquals(3, $fh->table_height, "HEIGHT");
        $this->assertEquals([0,1], $fh->cols, "COLS");
        $this->assertEquals(['','', ''], $fh->rows, "ROWS");
        $this->assertEquals($fh->data[0], [[0 => ''], [0 =>'']]);
        $this->assertEquals($fh->data[1], [[0 => 'A1'], [0 =>'A2']]);
        $this->assertEquals($fh->data[2], [[0 => 'B1'], [0 =>'B2']]);
    }
}