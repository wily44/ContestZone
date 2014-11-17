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
ob_start();
if(!isset($_SESSION)) {
    session_start();
}

/*
 * The following section is included for debugging purposes!
 * Comment this part out when running CZ, and set these session variables elsewhere.
 */
// We should write a template that does the basics.
$_SESSION['user'] = 'username';
$_SESSION['id'] = 1;
$_SESSION['contest_id'] = 1;
$_SESSION['num_problems'] = 1;
$_SESSION['contest_name'] = 'Test Contest #1';
$_SESSION['contest_end'] = 1418700011;
// END DEBUG MODE

if(isset($_POST['get_contest_id'])) {
	require_once('mysqlLogin.php');
	if(mysql_login(7)) {
		$_SESSION['contest_id'] = mysql_real_escape_string($_POST['get_contest_id']);
		$result = mysql_query("SELECT contest_name, end_time, number_problems FROM contests_tb11 WHERE contest_id=".$_SESSION['contest_id'].";");
		$arr = mysql_fetch_array($result);
		$_SESSION['contest_name'] = $arr['contest_name'];
		$_SESSION['contest_end'] = strtotime($arr['end_time']);
		$_SESSION['num_problems'] = $arr['number_problems'];
	}
}
date_default_timezone_set('America/Denver');
?>
<!DOCTYPE html>
<!--[if IE 9]><html class="ie9"><![endif]-->
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="contest_style.css" type="text/css">
		<script src="script.js" type="text/javascript"></script>
		<title>ContestZone</title>
		<link rel="shortcut icon" href="/favicon.ico" />
	</head>
	<body>
		<?php //for the alert box
			if(isset($_COOKIE["log"]) && $_COOKIE["log"] != "") {
				echo '<div id="alert_box" onclick="retreatUp(this, -1, -40)" onmouseover="document.body.style.cursor=\'pointer\'" onmouseout="document.body.style.cursor=\'auto\'">';
				echo '<span id="alert_inside"><span style="color:red">'.$_COOKIE["log"]."</span> <span style='font-size:10px'>(click to get rid of this alert box)</span></span>";
				echo '</div>';
				setcookie("log", "", time() - 3600);
			}
		?>
		<div id="wrapper">
			<aside class="shift_right" id="login_box">
				<?php
					//session_start();
					echo "<div id='log_box'>";

					if($_SESSION["user"]) {
						$id = $_SESSION["id"];
						if($id) {
							echo "You are logged in as ".$_SESSION["user"].".";
						}
						else {
							echo "Web error #5. Please contact the webmaster.";
							/* don't "die" here because it would interrupt the
							   control flow of login_result_index.php and
							   logout_result_index.php
							*/
						}
					}
					else
						echo "You are not logged in.";
					echo "</div>";
				?>
			</aside>
			<header>
				<h1><a href="./">ContestZone</a></h1>
				<p id="description">
					is now in v3.0!
				</p>
				<nav>
					<ul>
<?php
						if(!$_SESSION["user"] || !$_SESSION["contest_id"]) {
							echo "<div id='content_center'><p>Sorry, you need to enter ContestZone from the main website.</p></div>";
							die();
						}
						require_once('mysqlLogin.php');
						if(mysql_login(8)) {
							$query = "SELECT contest_setup_id FROM contest_setup_tb11 WHERE user_id = ".$id." AND contest_id = ".$_SESSION["contest_id"];
							$result = mysql_query($query);
							if($result)
								$arr = mysql_fetch_array($result);
							if(!$arr) {
								echo "<div id='content_center'><p>Sorry, you need to log in with an account that's registered for this contest.</p></div>";
								die();
							}
							$query = "SELECT level from contest_setup_tb11 WHERE contest_id = ".$_SESSION['contest_id']." AND user_id = ".$_SESSION["id"];
							$result = mysql_query($query);
							$arr = mysql_fetch_array($result);
							$level = $arr[0];
							$contestInSession = $_SESSION["contest_id"]; // last_contest.start < time < last_contest.end
							$letters = '_ABCDEFGHIJKL';
							$codes = '0123456789ABC';
							if($level === "B" && $contestInSession) {
								for($i = 1; $i <= $_SESSION['num_problems']; $i++)
									echo '<li><a href="./?module=Problem&amp;d=B'.$codes{$i}.'">'.$letters{$i}.'</a></li>';
							}
							else if($contestInSession) {
								for($i = 1; $i <= $_SESSION['num_problems']; $i++)
									echo '<li><a href="./?module=Problem&amp;d=A'.$codes{$i}.'">'.$letters{$i}.'</a></li>';
							}
							echo '<li><a href="./?module=Page&d=QA" style="">QA</a></li><!--li><a href="./?module=Page&d=Room">My Room</a></li--><li><a href="./?module=Page&d=Status">Status</a></li><li><a href="./?module=Page&d=Codes">Recent</a></li>';
						}
?>
					</ul>
				</nav>
			</header>
			<section id="main_section">
				<!--div id="content_right">
					
				</div-->
				<div id="content_center"><?php
						echo "<h2>".$_SESSION['contest_name'];
						?>
						<hr />
						<span id='server_time'>Server time: </span>
						<span id='time_left'></span>
						<?php
						echo "</h2><hr />";
						if(isset($_GET['module'])) {
                            $mod = $_GET['module'];
                        }
                        else {
                            $mod = '';
                        }
						switch($mod) {
							case "Problem":
								$problemTypeCurrent = $_GET['d'];
								require('./loadproblem.php');
								break;
							case "Page":
								$title = $_GET['d'];
								switch($title) {
									case "Contests":
										require('./contests.php');
										break;
									case "QA":
										require('./questions.php');
										break;
									case "Room":
										require('./room.php');
										break;
									case "Status":
										require('./status.php');
										break;
									case "Codes":
										require('./codes.php');
										break;
								}
								break;
							case "Grade":
								$problemTypeCurrent = $_GET['d'];
								require('./grade.php');
								break;
							case "SubmitQ":
								require('./submit_question.php');
								break;
							case "Error":
								$err_id = $_GET['d'];
								$_SESSION['submission_id'] = $err_id;
								require('./getError.php');
								break;
							default:
								require('./landing.php');
								break;
						}
					?>
				</div>
			</section>
		</div>
		<script type="text/javascript">
			function addZero(n) {
				if(n < 10)
					return "0" + n;
				return n;
			}
<?php
			echo 'var serverTime = '.(time() * 1000).';';
?>
			var running = false;
			var startDate = new Date();
			function updateTimeLeft() {
<?php
				echo 'var end = '.($_SESSION['contest_end'] * 1000).';';
?>
				var currentDate = new Date();
				var elapsedms = currentDate.getTime() - startDate.getTime();
				var timeLeft = end - (serverTime + elapsedms);
				var milli = Math.max(0, timeLeft);
				var seconds = Math.floor(milli / 1000);
				var msg = "";
				if(seconds <= 60) msg = " (Hurry!)"
				var minutes = Math.floor(seconds / 60);
				seconds %= 60;
				var hours = Math.floor(minutes / 60);
				minutes %= 60;
				var days = Math.floor(hours / 24);
				hours %= 24;
				document.getElementById('time_left').innerHTML = "Time left: "
						+ addZero(days) + ":"
						+ addZero(hours) + ":"
						+ addZero(minutes) + ":"
						+ addZero(seconds) + msg;
                //document.getElementById('time_left').innerHTML="Solutions will be accepted until 2:30.";
				if(milli >= 1e-7) {
					running = true;
					var t = setTimeout("updateTimeLeft()", 100);
				}
				else if(running) {
					//alert("The contest is over. Please refresh the page.");
				}
			}
			updateTimeLeft();

<?php
			echo 'var lastSunday = '.(strtotime("last Sunday") * 1000).';';
	//warning!! - undefined behavior over new year's. write a better script or don't have a contest over new year's :D
?>
			function updateServerTime() {
				var currentDate = new Date();
				var elapsedms = currentDate.getTime() - startDate.getTime();
				var milli = serverTime + elapsedms + 1000 - lastSunday; //the line in question
				var seconds = Math.floor(milli / 1000);
				var minutes = Math.floor(seconds / 60);
				seconds %= 60;
				var hours = Math.floor(minutes / 60);
				minutes %= 60;
				hours %= 24;
				document.getElementById('server_time').innerHTML = "Server time: "
						+ addZero(hours) + ":"
						+ addZero(minutes) + ":"
						+ addZero(seconds);
				if(milli >= 1e-7)
					var t = setTimeout("updateServerTime()", 100);
			}
			updateServerTime();
		</script>
	</body>
</html>
<?php ob_flush();?>
