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
	//Gets user stats for the contest
	//structure: 0: number of stats, 1...12(max) points for that problem, last: total points (total points is deprecated)
	function getUserStats($userID, $contestID, $userLevel) {
		require_once('mysqlLogin.php');
		if(mysql_login(12)) {

		$points = 0;
		$query = "SELECT problems_tb11.points, submissions_tb11.problem_type, submissions_tb11.time_graded FROM problems_tb11, submissions_tb11, users_tb11, contests_tb11
				WHERE ".$userID." = users_tb11.id
					AND users_tb11.id = submissions_tb11.author_id
					AND problems_tb11.contest_id = submissions_tb11.contest_id
					AND problems_tb11.problem_type = submissions_tb11.problem_type
					AND submissions_tb11.contest_id = ".$contestID."
					AND submissions_tb11.problem_type LIKE '".$userLevel."_'
					AND contests_tb11.contest_id = ".$contestID."
					AND submissions_tb11.counts_as = 'during'
				ORDER BY problems_tb11.points ASC";
		$result = mysql_query($query);
		$pr = mysql_fetch_array(mysql_query("SELECT number_problems FROM contests_tb11 WHERE contest_id = ".$contestID));
		$relate = array("1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6,"7"=>7,"8"=>8,"9"=>9,"A"=>10,"B"=>11,"C"=>12);
		$numProblems = $pr[0];
		$ret = array();
		$ret[] = $numProblems;
		for($i = 1; $i <= $numProblems; $i++)
			$ret[$i] = 0;
		while($result && ($arr = mysql_fetch_array($result))) {
			if($arr[1])
				if(strcmp($arr[2], "0000-00-00 00:00:00") == 0) {
					if(strcmp($ret[$relate[substr($arr[1], 1, 1)]], "<span style='color:green;'>OK</span>") != 0) //not overwriting something
						$ret[$relate[substr($arr[1], 1, 1)]] = "<span style='color:red;'>NO</span>";
				}
				else {
					$points += $arr[0];
					$ret[$relate[substr($arr[1], 1, 1)]] = "<span style='color:green;'>OK</span>";
				}
		}
		$ret[] = $points;
		return $ret;
		
		}
	}
?>
