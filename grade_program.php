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
//note: www-data needs to be in visudo with nopassword option set to on
$reldir = "./run_program/p/p".$_SERVER['argv'][1]."/";
$absdir = "/var/www/ContestZone/run_program/p/p".$_SERVER['argv'][1]."/";

$connect = mysql_connect("localhost", "root", "pass");
if(!connect) {
	exec("rm -rf ".$absdir);
	die();
}
mysql_select_db("contestzone");
$result = mysql_query("SELECT submissions_tb11.submission_id, submissions_tb11.problem_type, submissions_tb11.code, submissions_tb11.language
		, submissions_tb11.author_id, submissions_tb11.time_graded, contests_tb11.contest_id, submissions_tb11.counts_as FROM contests_tb11, submissions_tb11
		WHERE is_graded = 0 AND submissions_tb11.contest_id=contests_tb11.contest_id ORDER BY submissions_tb11.submission_id ASC LIMIT 3");
if(!$result) {
	die();
}
$row = mysql_fetch_array($result);
if(!$row) {
	//echo "No submissions in queue.";
	die();
}

exec("sudo mkdir ".$absdir); //make the folder
exec("sudo chmod -R 777 ".$absdir); //has to chmod to let test_program.php make stats on user program_tester

$submission_id = $row[0];

mysql_query("UPDATE submissions_tb11 SET is_graded = 1 WHERE submission_id = ".$submission_id);

$problemTypeCurrent = $row[1];
$level = substr($problemTypeCurrent, 0, 1);
$code = $row[2];
$codeMod = stripslashes(trim($code));
$language = trim($row[3]);
$uid = $row[4];
$contestID = $row[6];
$counts_during = strcmp("during", $row[7]) == 0;

$result = mysql_query("SELECT submission_id from submissions_tb11 WHERE author_id=".$uid." AND contest_id=".$contestID
			." AND time_graded NOT LIKE '0000-00-00 00:00:00' AND problem_type='".$problemTypeCurrent."';");
$arr=array();
$submitTwice = false;
if($result) {
	$arr=mysql_fetch_array($result);
	if($arr)
		$submitTwice = true;
}

/* //Still grade double submissions, but leave their time_graded as 0 so as to not count their points later

if(strcmp($row[5], "0000-00-00 00:00:00") != 0) {
	//echo "Someone submitted twice.<br />";
	mysql_query("UPDATE submissions_tb11 SET verdict = 'SUBMIT TWICE (OK)' WHERE submission_id = ".$submission_id);
	exec("rm -rf ".$absdir);
	die();
}

*/

$query = "SELECT problems_tb11.problem_id, problems_tb11.code, problems_tb11.points FROM contests_tb11, problems_tb11
		WHERE contests_tb11.contest_id=".$contestID." AND problems_tb11.problem_id=contests_tb11.problem_".$problemTypeCurrent."_id;";
$result = mysql_query($query);
$problems = mysql_fetch_array($result);
$problemID = $problems[0];
$solutionCode = $problems[1];
$solutionCodeMod = stripslashes(trim($solutionCode));
$points = $problems[2];

$correct = true;




$java = strcmp($language, "Java") === 0;
$cpp = strcmp($language, "C++") === 0;
$py = strcmp($language, "Python") === 0;

$cmd = "";
//$statdir = "/var/www/run_program_stats/";

$chrootdir = '/p/p'.$_SERVER['argv'][1]."/";

if($cpp) {
	$chrootpathcmp = $chrootdir."Main.cpp";
	file_put_contents($absdir."Main.cpp", $codeMod);
	exec("sudo chmod 775 ".$absdir."Main.cpp");
	$cmd = 'sudo chroot /var/www/ContestZone/run_program/ su -c \'perl -w timeout.pl -t 5 "g++ -O2 -w -o '
			.$chrootdir.'a.out '.$chrootdir.'Main.cpp 2> '.$chrootdir.'compile_error.err 1> '
			.$chrootdir.'compile.log" > '.$chrootdir.'compilestats.txt\' - program_tester';
}
else if($java) {
	$chrootpathcmp = $chrootdir."Main.java";
	file_put_contents($absdir."Main.java", $codeMod);
	exec("sudo chmod 775 ".$absdir."Main.java");
	$cmd = 'sudo chroot /var/www/ContestZone/run_program/ su -c \'perl -w timeout.pl -t 5 "javac -Xlint:unchecked -nowarn -d '
			.$chrootdir.' '.$chrootdir.'Main.java 2> '
			.$chrootdir.'compile_error.err 1> '.$chrootdir.'compile.log" 2> '
			.$chrootdir.'compilestats.txt\' - program_tester';
	echo $cmd;
}
else if($py) {
	$chrootpath = $chrootdir."Main.py";
	file_put_contents($absdir."Main.py", $codeMod);
}

if(strcmp("", $cmd) != 0) {
	//echo $cmd."<br />";
	exec($cmd);
	$ret = file_get_contents($absdir."compilestats.txt");
	$arr = explode("\n", $ret);
	foreach($arr as $proc) {
		//echo $proc."<br />";
		$boom = explode(" ", $proc);
		if(strcmp("FINISHED", $boom[0]) == 0) {
			//echo "Compiled successfully.<br />";
		}
		else if(strcmp("TIMEOUT", $boom[0]) == 0) {
			//echo "Compile timed out.<br />";
			$correct = false;
			$compile_tl = true;
		}
		else if(strcmp("HANGUP", $boom[0]) == 0) {
			//echo "Compile hung up.<br />";
			$correct = false;
			$compile_tl = true;
		}
	}
	if($compile_tl) {
		file_put_contents($absdir."verdict.txt", "CT");
	}
	else {
		$errorchk = ((!file_exists($absdir."compile_error.err"))
				|| (strcmp(file_get_contents($absdir."compile_error.err"), "") === 0));
		if(!$errorchk) {
			file_put_contents($absdir."verdict.txt", "CE");
		}
	}
} 
if($cpp) {
	exec("sudo chmod 775 ".$absdir."a.out");
	$chrootpath = $chrootdir."a.out";
	$langpathcmp = $absdir."Main.cpp";
	$langpath = $absdir."a.out";
}
else if($java) {
	exec("sudo chmod 775 ".$absdir."Main.class");
	$chrootpath = $chrootdir."Main.class";
	$langpathcmp = $absdir."Main.java";
	$langpath = $absdir."Main.class";
}
else if($py) {
	exec("sudo chmod 775 ".$absdir."Main.py");
	$langpath = $absdir."Main.py";
}

if(file_exists($absdir."verdict.txt"))
	$correct = false;

if($correct) {

$getTesters = "SELECT input, output from testers_tb11 WHERE problem_id = ".$problemID." ORDER BY tester_id ASC";
$result = mysql_query($getTesters);
$wa_number = 0;
$countTests = 0;
while($result && !file_exists($absdir."verdict.txt") && ($tester = mysql_fetch_array($result))) {
	$countTests++;
	file_put_contents($absdir."grade.in", $tester[0]."\n");
	exec("php -f test_program.php ".$language." ".$_SERVER['argv'][1]);	// run the program

	file_put_contents($absdir."correct.out", $tester[1]);

	// test it against actual output
	$errorchk = ((!file_exists($absdir."runtime_error.err")) || (strcmp(file_get_contents($absdir."runtime_error.err"), "") === 0));
	if(!$errorchk) {
		$rnterr = file_get_contents($absdir."runtime_error.err");
		//echo "<span style='color:red;'>Runtime error:</span><pre>".str_replace("grade", "PROBLEM", $rnterr)."</pre><br />";
		//$correct = false;
		file_put_contents($absdir."verdict.txt", "RE");
		//file_put_contents("rterr.txt", $rnterr);
	}
	else if(!file_exists($absdir."verdict.txt")) {
	
		$value = trim(file_get_contents($absdir."grade.out")); //IMPORTANT - output is trimmed
		$value = str_replace(array("\r\n","\r","\n"),"\r\n",$value); //standardize newlines to windows line breaks - meaning THIS WILL ONLY WORK IF YOU'RE INPUTTING TEST CASES FROM A WINDOWS COMPUTER
		file_put_contents("auto_t.txt", $value);
		//file_put_contents("correct.out", trim(file_get_contents($absdir."correct.out")));
		
		
		//echo "Input:<br />".$tester[0]."<br />Received:<br /><pre>".$value."</pre><br />Expected:<br /><pre>".$tester[1]."</pre><br />";
		$correctOut = trim(file_get_contents($absdir."correct.out"));
		//$correctOut = str_replace(array("\r\n","\r","\n"),"\r\n",$correctOut); // this replaces \r\n with \r\r\n - so be careful
		if(strcmp($value, $correctOut) != 0) {
			$correct = false;
			//mysql_query();
			//echo "<span style='color:red;'>FAIL</span><br />";
			file_put_contents($absdir."verdict.txt", "WA");
			$wa_number = $countTests;
		}
	}
}//end testing loop

if($countTests == 0 && !file_exists($absdir."verdict.txt")) {
	//echo "<p>There's been a problem with the contest setup. Call someone over.</p>";
	//$correct = false;
	mysql_query("UPDATE submissions_tb11 SET verdict = 'TA' WHERE submission_id = ".$submission_id);
	exec("rm -rf ".$absdir);
	die();
}
}//end if($correct)

if(!file_exists($absdir."verdict.txt")) { //IMPORTANT - takes the earliest correct submission as the graded time
	$update_latest = $submitTwice ? "" : ", time_graded='".date('Y-m-d H:i:s')."'";
	mysql_query("UPDATE submissions_tb11 SET verdict='AC' ".$update_latest." WHERE submission_id = ".$submission_id);
	$arr = mysql_fetch_array(mysql_query("SELECT total_points_".strtolower($level)." FROM contest_setup_tb11 WHERE contest_id=".$contestID." AND user_id = ".$uid)); //add points to total
	if($counts_during) {
		$newPoints = $arr[0] + $points;
		if(!$submitTwice)
			mysql_query("UPDATE contest_setup_tb11 SET last_time_graded='".date('Y-m-d H:i:s')."', total_points_".strtolower($level)." = ".$newPoints." WHERE contest_id=".$contestID." AND user_id=".$uid);
		//echo "<p>Passed tests yay!</p>";
	}
}
else {
	//echo "<p>Failed tests</p>";
	$err = trim(file_get_contents($absdir."verdict.txt"));
	if(strcmp($err, "WA") === 0)
		mysql_query("UPDATE submissions_tb11 SET verdict='".$err."', error_message='Wrong answer on test ".$wa_number."' WHERE submission_id = ".$submission_id);
	else if(strcmp($err, "CE") === 0)
		mysql_query("UPDATE submissions_tb11 SET verdict='".$err."', error_message='".mysql_real_escape_string(str_replace($langpathcmp, "/", str_replace($chrootpathcmp, "/", file_get_contents($absdir."compile_error.err"))))."' WHERE submission_id = ".$submission_id);
	else
		mysql_query("UPDATE submissions_tb11 SET verdict='".$err."', error_message='Runtime error on test ".$countTests."\n".mysql_real_escape_string(str_replace($langpath, "/", str_replace($chrootpath, "/", file_get_contents($absdir."runtime_error.err"))))."' WHERE submission_id = ".$submission_id);
}

exec("rm -rf ".$absdir);

?>
