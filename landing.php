<?php
/*
   Copyright 2012 Wilbur Yang

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
<?php
//Content goes after this line
?>
<br /><p>Click on the above links to enter.</p>
<h2>General Rules & Guidelines</h2>
<p>We are using standard in and out.<br />
Java and C++ programs have a 1 second time limit; Python programs have 3 seconds.<br />
Java - Remember to name your class Main and to copy in all your imports.<br />
Python - Remember to always trim() your input.</p>

<h2>Verdict Key</h2>
<p>AC - Accepted<br />
WA - Wrong Answer<br />
TL - Time Limit Exceeded<br />
CE - Compile Error<br />
RE - Runtime Error<br />
TA - Tell An Administrator (through QA page)</p>