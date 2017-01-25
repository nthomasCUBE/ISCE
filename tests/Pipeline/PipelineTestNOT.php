<?php

use ISCE\Pipeline;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PipelineTest extends \PHPUnit_Framework_TestCase
{

    # The Application starts the Pipeline
    public function testRunPipelineWithCorrectInput()
    {
        define('__VER__', '1.1');
        define('__DATE__', '18 Dec. 2015');
        $application = new Application();
        $application->add(new Pipeline());

        $command = $application->find('run');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command'      => $command->getName(),
            'locusA'         => 'someA',
            'locusB'         => 'someB'
        ));

        $this->assertRegExp('/Inter Species Co-Evolution Pipeline/', $commandTester->getDisplay());
    }


}