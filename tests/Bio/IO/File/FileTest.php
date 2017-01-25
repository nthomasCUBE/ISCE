<?php

use Bio\IO\File\File;
use Bio\IO\File\FileException;

class FileTest extends \PHPUnit_Framework_TestCase
{

    public function testFileContructor()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/test1.txt';
        $fh = new File($fileA);
        $this->assertEquals($fh->name(), $fileA);
    }

    public function testWOFilename()
    {
        try {
            $fh = new File();
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameNull()
    {
        try {
            $fileA = NULL;
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameEmpty()
    {
        try {
            $fileA = '';
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameSpace()
    {
        try {
            $fileA = ' ';
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameWhitespace()
    {
        try {
            $fileA = "\n";
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameFalse()
    {
        try {
            $fileA = false;
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testFilenameTrue()
    {
        try {
            $fileA = true;
            $fh = new File($fileA);
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("Given filename is not allowed", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testReadableToArray()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/test1.txt';
            $fh = new File($fileA);
            $fh->toArray();
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("File doesn't exist or is read protected", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testToString()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/test2.txt';
        $fh = new File($fileA);
        $string = $fh->toString();
        $this->assertEquals(file_get_contents($fileA), $string);
    }

    public function testToArray()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/test2.txt';
        $fh = new File($fileA);
        $array = $fh->toArray();
        $this->assertEquals(file($fileA, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES), $array);
    }

    public function testReadableToString()
    {
        try {
            $fileA = 'tests/Bio/IO/File/testFiles/test1.txt';
            $fh = new File($fileA);
            $fh->toString();
        }
        catch (\Exception $e) {
            $this->assertStringStartsWith("File doesn't exist or is read protected", $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testToStringToArray()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/test3.txt';
        $fh1 = new File($fileA);
        $fh2 = new File($fileA);

        $array = $fh1->toArray();
        $string = $fh2->toString();
        $this->assertEquals(explode("\n",$string), $array);
    }

    public function testToStringToArrayWithEmtyLine()
    {
        $fileA = 'tests/Bio/IO/File/testFiles/test4.txt';
        $fh1 = new File($fileA);
        $fh2 = new File($fileA);

        $array = $fh1->toArray();
        $string = $fh2->toString();
        $this->assertEquals(array_merge(array_filter(explode("\n",$string)), []), $array);
    }
}

