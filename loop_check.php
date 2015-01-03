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

//die(); // remove comment during a contest
ob_start();
set_time_limit(0);

$i = rand(10000000, 50000000);
while(true) {

  $i++;
  if($i == 1500000000) $i = 10000000;

  //exec("php -f auto_grade_submit.php ".$i." > /dev/null 2>&1 &");
  exec("php -f grade_program.php ".$i); //NOT A BACKGROUND PROCESS
  usleep(200000);
}


ob_flush();
?>
