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
	if(!$_SESSION["user"]) {
		die();
	}
?>
<?php
	//IF YOU'RE AN ADMINISTRATOR, YOU CAN SEE EVERYONE ELSE'S CODE DURING THE COMPETITION!
	require('./grabUserContest.php');
	$contestID = $_SESSION['contest_id'];
	require_once('mysqlLogin.php');
	if(mysql_login(12)) {
	$query = "SELECT users_tb11.username, submissions_tb11.problem_type, submissions_tb11.language
			, submissions_tb11.verdict, submissions_tb11.time_submitted, submissions_tb11.submission_id
			, submissions_tb11.counts_as
			FROM users_tb11, submissions_tb11 WHERE users_tb11.id = submissions_tb11.author_id
            AND users_tb11.username = '".$_SESSION["user"]."'
			AND users_tb11.type NOT LIKE 'administrator'
			AND submissions_tb11.contest_id=".$contestID."
			ORDER BY submissions_tb11.submission_id DESC LIMIT 50;";
	$result = mysql_query($query);
	if($result) {
		echo "<br /><table><tr><th>#</th><th>User</th><th>Problem</th><th>Language</th>
			<th>Verdict</th><th>Time Submitted</th><th>Why?</th></tr>";
		$c = 0;
		while($arr = mysql_fetch_array($result)) {
			echo "<tr class='tr".($c % 2)."'><td>".$arr[5]."</td><td>".userLink(htmlentities($arr[0]))."</td><td>".$arr[1]."</td><td>".$arr[2]."</td>";
			$verdict = $arr[3];
			if($verdict === "")
				echo "<td>(refresh)...</td>";
			else if($verdict === "AC")
				echo "<td><span style='color:green;'>".$verdict."</span></td>";
			else if($verdict === "WA" || $verdict === "CE"
				|| $verdict === "RE" || $verdict === "TL"
				|| $verdict === "CT")
				echo "<td><span style='color:red;'>".$verdict."</span></td>";
			else
				echo "<td>".$verdict."</td>";
			echo "<td>".$arr[4]."</td><td><a href='./?module=Error&d=".$arr[5]."'>".$arr[5]."</a></td>";
			$c++;
		}
		echo "</table>";
	}

	}
?>
