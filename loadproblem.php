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
	//session_start();
	if(!$_SESSION["user"]) {
		die();
	}
?>
<!--Content goes after this line-->
<?php
global $problemTypeCurrent;

$contestID = $_SESSION['contest_id'];


require_once('mysqlLogin.php');
if(mysql_login(9)) {


//get the problem statement
$query = "SELECT problems_tb11.statement, problems_tb11.problem_name FROM contests_tb11, problems_tb11 WHERE contests_tb11.contest_id=".$contestID." AND problems_tb11.problem_id=contests_tb11.problem_".$problemTypeCurrent."_id;";
$result = mysql_query($query);

if($result) {
	$problem_statements = mysql_fetch_array($result);
	if(!$problem_statements)
		$problem_statements = array(0 => "", "");
}

//get the saved submission if there is one
$query = "SELECT submissions_tb11.code, submissions_tb11.language FROM contests_tb11, submissions_tb11, users_tb11 WHERE contests_tb11.contest_id=".$contestID." AND users_tb11.username='".$_SESSION['user']."' AND users_tb11.id=submissions_tb11.author_id AND submissions_tb11.contest_id=contests_tb11.contest_id AND submissions_tb11.problem_type='".$problemTypeCurrent."' ORDER BY submissions_tb11.time_submitted DESC LIMIT 5;";
$result = mysql_query($query);
if($result)
	$submissions = mysql_fetch_array($result);
else $submissions = array(0 => "", "");

$language = $submissions[1];
$j = (strcmp($language, "Java") === 0) ? "selected='selected'" : "";
$c = (strcmp($language, "C++") === 0) ? "selected='selected'" : "";
$p = (strcmp($language, "Python") === 0) ? "selected='selected'" : "";

$problem = array("B1" => "Beginner-A", "B2" => "Beginner-B", "B3" => "Beginner-C", "B4" => "Beginner-D", "B5" => "Beginner-E"
		, "B6" => "Beginner-F", "B7" => "Beginner-G", "B8" => "Beginner-H", "B9" => "Beginner-I", "BA" => "Beginner-J"
		, "BB" => "Beginner-K", "BC" => "Beginner-L"
		, "A1" => "Advanced-A", "A2" => "Advanced-B", "A3" => "Advanced-C", "A4" => "Advanced-D", "A5" => "Advanced-E"
		, "A6" => "Advanced-F", "A7" => "Advanced-G", "A8" => "Advanced-H", "A9" => "Advanced-I", "AA" => "Advanced-J"
		, "AB" => "Advanced-K", "AC" => "Advanced-L");

//print the h2
echo "<h2>".$problem[$problemTypeCurrent]." Problem: ".$problem_statements[1]."</h2>";

if(!trim($problem_statements[0])) {
echo "<br /><p>sorry, this problem does not exist.</p>";
}
else {
//print it out (notice htmlentities is used with the saved solution in the database)
echo "<form action='./?&module=Grade&d=".$problemTypeCurrent."' method='post'><p id='showproblem'>".trim($problem_statements[0])."</p>
<hr /><p>Submit (We recommend using an IDE so that you don't lose any code):</p>
<p>Language:
<select name='language'>
	<option value='Java' ".$j." >Java</option>
	<option value='C++' ".$c." >C++</option>
	<option value='Python' ".$p." >Python</option>
</select></p>
<textarea name='submission' rows='20' cols='80'>".stripslashes(htmlentities($submissions[0], ENT_COMPAT, 'utf-8'))."</textarea><br /><input type='submit' value='Save and submit for testing'></input></form>";
}

}

?>
