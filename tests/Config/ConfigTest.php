<?php

use ISCE\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testConfigInitWithoutConfigFile()
    {
        try {
            new Config();
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith('Could not find config-file: ', $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConfigInitWithNotExistingConfigFile()
    {
        try {
            new Config('myConfigPathIsWrong.json');
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith('Could not find config-file: ', $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConfigWithWrongKey()
    {
        try {
            $config = new Config(__DIR__ . '/correct.config.json');
            $this->assertEquals($config->get('pathWRONG.A'), '/full/path/to/A/');
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith('Config get() Key-Error: pathWRONG.A', $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConfigWithIncorrectJSON()
    {
        try {
            $config = new Config(__DIR__ . '/incorrect.config.json');
            $this->assertEquals($config->get('pathWRONG.A'), '/full/path/to/A/');
        } 
        catch (\Exception $e) {
            $this->assertStringStartsWith('Not a valid json format in: ', $e->getMessage());
            return;
        }
        $this->fail();
    }

    public function testConfigWithCorrectConfigFile()
    {
        $config = new Config(__DIR__ . '/correct.config.json');
        $this->assertEquals($config->get('path.A'), '/full/path/to/A/');
        $this->assertEquals($config->get('files.fileB'), '/full/path/to/File/B');
    }
}