<?php
/*
   Copyright 2014 Wilbur Yang

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/
//note: grade_program.php and test_program.php are the 2 programs in the grading schema
$number = $_SERVER['argv'][2];
$absdir = "/p/p".$number."/";
$chroot = "/var/www/programming/contestzone/run_program";

$st = 0;

$language = $_SERVER['argv'][1];

$java = strcmp($language, "Java") === 0;
$cpp = strcmp($language, "C++") === 0;
$py = strcmp($language, "Python") === 0;

chdir("/var/www/programming/contestzone/run_program/");
if($cpp) {
	$cmd = 'sudo chroot . su -c "perl -w timeout.pl -t 1 -m 64000 \''.$absdir.'a.out < '.$absdir.'grade.in > '
			.$absdir.'grade.out 2> '.$absdir.'runtime_error.err\' 2> '
			.$absdir.'runtimestats.txt" - program_tester';
}
else if($java) {
	$cmd = 'sudo chroot . su -c "perl -w timeout.pl -t 1 \'/usr/lib/jvm/java-6-sun-1.6.0.06/jre/bin/java -client -Xmx64m -classpath '
			.$absdir.' Main < '.$absdir.'grade.in > '
			.$absdir.'grade.out 2> '.$absdir.'runtime_error.err\' 2> '
			.$absdir.'runtimestats.txt" - program_tester';
}
else if($py) {
	$cmd = 'sudo chroot . su -c "perl -w timeout.pl -t 3 -m 64000 \'/usr/bin/python2.5 -W ignore '.$absdir
			.'Main.py < '.$absdir.'grade.in > '
			.$absdir.'grade.out 2> '.$absdir.'runtime_error.err\' 2> '
			.$absdir.'runtimestats.txt" - program_tester';
}
if(strcmp("", $cmd) != 0) {
	//echo $cmd."<br />";
	exec($cmd);
	$ret = file_get_contents($chroot.$absdir."runtimestats.txt");
	$arr = explode("\n", $ret);
	foreach($arr as $proc) {
		//echo $proc."<br />";
		$boom = explode(" ", $proc);
		if(strcmp("FINISHED", $boom[0]) == 0) {
			//echo "Ran successfully.<br />";
		}
		else if(strcmp("TIMEOUT", $boom[0]) == 0) {
			//echo "Run timed out.<br />";
			$run_tl = true;
		}
		else if(strcmp("HANGUP", $boom[0]) == 0) {
			//echo "Run hung up.<br />";
			$run_tl = true;
		}
	}
	if($run_tl)
		file_put_contents($chroot.$absdir."verdict.txt", "TL");
}
?>
