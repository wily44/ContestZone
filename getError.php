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

require_once('mysqlLogin.php');
if(mysql_login(11)) {

$id = $_SESSION['id'];
$submission_id = $_SESSION['submission_id'];

$admin_bypass = false;

$res = mysql_query("SELECT type FROM users_tb11 WHERE id = ".$id);
if($res) {
	$arr = mysql_fetch_array($res);
	if(strcmp($arr[0], "administrator") === 0)
		$admin_bypass = true;
}

$query = "SELECT code, error_message, author_id FROM submissions_tb11 WHERE submission_id = ".$submission_id;
if($res = mysql_query($query)) {
	$arr = mysql_fetch_array($res);
}
if(!arr) {
	echo "<p>Sorry, submission not found.</p>";
}
else if(!$admin_bypass && strcmp($arr[2], $id) != 0) {
	echo "<p>Sorry, you can only view your own submissions and error messages.</p>";
}
else {
	$err = stripslashes(trim(htmlentities($arr[1], ENT_COMPAT, 'utf-8')));
	echo "<h2>Code:</h2>";
	echo "<textarea rows='20' cols='80' readonly='readonly'>".stripslashes(trim(htmlentities($arr[0], ENT_COMPAT, 'utf-8')))."</textarea>";
	if($err) {
		echo "<h2>Error message:</h2>";
		echo "<textarea rows='10' cols='80' readonly='readonly'>".$err."</textarea>";
	}
}

}
?>
