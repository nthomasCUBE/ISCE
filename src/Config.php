<?php

namespace ISCE;

use Noodlehaus\Config as ConfigParser;

class Config{

    private $config = Null;

    public function __construct($config_file = Null)
    {

        if (!$config_file || !file_exists($config_file)) {
            throw new \Exception('Could not find config-file: '. $config_file);
        }

        if (!is_object(json_decode(file_get_contents($config_file)))) {
            throw new \Exception('Not a valid json format in: '. $config_file);
        }

        $this->config = new ConfigParser($config_file);
    }

    public function get($key = Null)
    {
        if (!$key || !$this->config->get($key)) {
            throw new \Exception('Config get() Key-Error: ' . $key);
        }
        
        return $this->config->get($key);
    }
}