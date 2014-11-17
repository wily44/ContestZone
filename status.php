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
	global $level;

	$contestID = $_SESSION['contest_id'];
	require("./stats.php");
	require("./grabUserContest.php");

	if($level === "B")
		echo "<h2>Beginner Division</h2>";
	else
		echo "<h2>Advanced Division</h2>";
	echo "<br />";
	require_once('mysqlLogin.php');
	if(mysql_login(13)) {

	$userIDList = array();
	$query = "SELECT DISTINCT users_tb11.id, users_tb11.username
		, contest_setup_tb11.last_time_graded, contest_setup_tb11.total_points_".strtolower($level)."
		FROM users_tb11, contest_setup_tb11
		WHERE contest_setup_tb11.contest_id = ".$contestID."
		AND users_tb11.type NOT LIKE 'administrator'
		AND users_tb11.id = contest_setup_tb11.user_id
		AND contest_setup_tb11.level = '".$level."'
		ORDER BY contest_setup_tb11.total_points_".strtolower($level)." DESC
		, contest_setup_tb11.last_time_graded ASC, users_tb11.username ASC";
	$countTable = 0;
	$result = mysql_query($query);
	$code = array("", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L");
	if($result) {
		$arr = mysql_fetch_array($result);
		//fill in headers
		echo "<table><tr><th>Rank</th><th>Username</th>";
		$uid = $arr[0];
		$stat = getUserStats($uid, $contestID, $level);
		$len = count($stat) - 2;
		for($i = 1; $i <= $len; $i++)
			echo "<th>".$code[$i]."</th>";
		echo "<th>Total</th><th>Last Graded</th>";
		echo "</tr>";
		//now for the first user
		echo "<tr class='tr0'><td>1</td>";
		echo "<td>".userLink(htmlentities($arr[1]))."</td>";
		$length = $stat[0];
		for($i = 1; $i <= $length; $i++)
			echo "<td>$stat[$i]</td>";
		$tot = $length + 1;
		echo "<td>$arr[3]</td>";
		echo "<td>".$arr[2]."</td>";
		echo "</tr>";
		while($result && ($arr = mysql_fetch_array($result))) {
			$countTable++;
			$uid = $arr[0];
			$stat = getUserStats($uid, $contestID, $level);
			if($countTable % 2 == 1)
				echo "<tr class='tr1'>";
			else
				echo "<tr class='tr0'>";
			echo "<td>".($countTable + 1)."</td><td>".userLink(htmlentities($arr[1]))."</td>";
			$length = $stat[0];
			for($i = 1; $i <= $length; $i++)
				echo "<td>$stat[$i]</td>";
			$tot = $length + 1;
			echo "<td>$arr[3]</td>";
			echo "<td>".$arr[2]."</td>";
			
			echo "</tr>";
		}
		echo "</table>";
	}
	}
?>
