<?php

	$path_to_your_file = '../runs/insilico/all.txt';

	$todo = file($path_to_your_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


	foreach($todo as $key => $task) {
		if ($key%100 == 0){ sleep(2); }
		shell_exec("sbatch starter.sh {$task}");
		echo $key . "\t" . $task ."\n";
	}