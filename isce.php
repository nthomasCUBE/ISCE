#!/usr/bin/php5

<?php

error_reporting(E_ALL);
ini_set('display_errors',1);
date_default_timezone_set ('Europe/Vienna');
/**
 * Inter Species Co-Evolution Pipeline
 * 
 * @version 1.7
 * @author Klaus Rembart <klaus@rembart.at>
 *
 * This Pipeline uses your orthoFinder results (of two species) 
 * and detect co-ecolving positions between given proteins.
 */

/**
 * Autoload (PSR-4) the vendor and src files
 */
require __DIR__ . '/vendor/autoload.php';

define('__VER__', '2.3');
define('__DATE__', '31 Mai. 2016');
define('__TIME__', microtime(true));
define('__DEBUG__', true);

/**
 * Basic Classes to run the Pipeline.
 * Namespace "ISCE" is equivalent to the /src folder.
 * All other Namespaces refer to the /vendor folder.
 */
use ISCE\Pipeline;
use Symfony\Component\Console\Application;

/**
 * A instance of the Symfony Console Application.
 * @var Application
 */
$app = new Application();

/**
 * Bind a Pipeline Object to the Application and run it.
 */

$app->add(new Pipeline());
$app->run();


