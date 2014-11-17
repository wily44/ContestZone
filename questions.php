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
    if(!isset($_SESSION)) {
        session_start();
    }
	if(!$_SESSION["user"] || !$_SESSION["contest_id"]) {
		die();
	}
?>
<?php
mysql_connect("localhost", "root", "pass");
mysql_select_db("contestzone");
$result = mysql_query("SELECT broadcast, time_a, title
		FROM broadcasts_tb11
		WHERE contest_id=".$_SESSION["contest_id"]
		." ORDER BY time_a DESC LIMIT 30;");
echo "<h2>Broadcasts</h2>";
if($result) {
	$count = 0;
	while($arr = mysql_fetch_array($result)) {
		//$arr[0] = stripslashes($arr[0]);
		$count++;
		echo "<h3>".htmlentities(stripslashes($arr[2]))."</h3><br /><p>".htmlentities($arr[0])."<br /><br />-sent at "
				.htmlentities(stripslashes($arr[1]))."</p><hr />";
	}
}
if($count == 0)
	echo "<p>There have been no broadcasts.</p><hr />";
$result = mysql_query("SELECT question, answer, time_a
		FROM questions_tb11
		WHERE user_id=".$_SESSION['id']."
		AND answer NOT LIKE ''
		AND contest_id=".$_SESSION["contest_id"]
		." ORDER BY time_a DESC LIMIT 30;");
echo "<h2>Recent Questions</h2>";
if($result) {
	$count = 0;
	while($arr = mysql_fetch_array($result)) {
		//$arr[1] = stripslashes($arr[1]);
		$count++;
		echo "<h3>Q</h3><p>".htmlentities(stripslashes($arr[0]))."</p><h3>A</h3><p>"
				.htmlentities($arr[1])."<br /><br />-answered at "
				.htmlentities(stripslashes($arr[2]))."</p><hr />";
	}
}
if($count == 0)
	echo "<p>You have not received any responses yet.</p><hr />";
?>
<form action='./?module=SubmitQ' method='post'>
<h2>Submit a question</h2>
<p>(Everyone will be able to see the response on the QA page if the clarification is important.)</p>
<textarea name='get_question' rows='10' cols='80'></textarea>
<br /><input type='submit' value='Submit'/>
</form>
