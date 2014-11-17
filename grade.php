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
	if(!($_SESSION["user"] && $_SESSION["contest_id"])) {
		die();
	}
?>
<!--Content goes after this line-->
<?php
date_default_timezone_set('America/Los_Angeles');
$time_snapshot = date('Y-m-d H:i:s');

global $problemTypeCurrent; //the problem type is given in the url
global $language;           //and language

$contestID = $_SESSION['contest_id'];

require_once('mysqlLogin.php');
if(mysql_login(10)) {

$id = $_SESSION['id'];

$query = "SELECT contest_setup_id FROM contest_setup_tb11 WHERE user_id = ".$id." AND contest_id = ".$contestID;
$arr = mysql_fetch_array(mysql_query($query));
if(!$arr)
	echo "<p>Sorry, you need to log in with an account that's registered for this contest.</p>";
else {
	/* ALWAYS INSERT A NEW ROW
	
	$query = "UPDATE submissions_tb11 SET is_graded=0, language='".mysql_real_escape_string(trim($_POST['language']))."', code='".mysql_real_escape_string(trim($_POST['submission']))."', time_submitted='".$time_snapshot."' WHERE contest_id=".$contestID." AND author_id=".$id." AND problem_type='".$problemTypeCurrent."';";
	$result = mysql_query($query);
	$exp = explode(' ', mysql_info());
	if(mysql_affected_rows() == 0 && $exp[2] == 0) {

	*/
	$submitted = false;
	$check = false;
	$r = mysql_query("SELECT * FROM contests_tb11 WHERE STRCMP(end_time, '".$time_snapshot."') >= 0 AND contest_id=".$contestID." LIMIT 5;");
	if($r) {
		if($a = mysql_fetch_array($r)) {
			$query = "INSERT INTO submissions_tb11(author_id, contest_id, problem_type, code, language, time_submitted, is_graded) values(".$id.",".$contestID.",'".$problemTypeCurrent."','".mysql_real_escape_string(trim($_POST['submission']))."', '".mysql_real_escape_string(trim($_POST['language']))."', '".$time_snapshot."', 0);";
			$result = mysql_query($query);
			$query = "SELECT submission_id FROM submissions_tb11 WHERE author_id=".$id." AND contest_id=".$contestID." AND problem_type LIKE '".$problemTypeCurrent."' AND time_submitted LIKE '".$time_snapshot."' AND is_graded=0;";
			//echo $query;
			$result = mysql_query($query);
			$arr = mysql_fetch_array($result);
			echo "<br /><p>Your solution has been sent successfully.</p>";
			$submitted = true;
			$check = true;
		}
	}
	if(!$submitted) {
		$r = mysql_query("SELECT * FROM contests_tb11 WHERE STRCMP(end_time, '".$time_snapshot."') < 0 AND contest_id=".$contestID." LIMIT 5;");
		if($r) {
			if($a = mysql_fetch_array($r)) {
				$query = "INSERT INTO submissions_tb11(author_id, contest_id, problem_type, code, language, time_submitted, is_graded, counts_as) values(".$id.",".$contestID.",'".$problemTypeCurrent."','".mysql_real_escape_string(trim($_POST['submission']))."', '".mysql_real_escape_string(trim($_POST['language']))."', '".$time_snapshot."', 0, 'after');";//change "during" back to "after"!
				$result = mysql_query($query);
			}
			echo "<br /><p>Sorry, time's up.</p>";
			$check = true;
		}
	}
	if(!$check) {
		echo "<br /><p>Something went wrong in the submission. Please tell an administrator.</p>";
	}
}


}
?>
