<?php

date_default_timezone_set("Asia/Yekaterinburg");
echo ini_get("post_max_size")."<br>";
echo "The current date ".date("F d Y H:i:s")."<br>";
echo "The last modify date ".date("F d Y H:i:s",getlastmod())."<br>";
$ip=$_SERVER['REMOTE_ADDR'];
echo "The ip address ".$ip."<br>";
$script=$_SERVER['PHP_SELF'];
echo "The script ".$script."<br>";

phpinfo();













?>
