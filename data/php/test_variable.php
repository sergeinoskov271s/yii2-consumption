<?php
	$a = 56;
	echo gettype($a).'<br />';
	settype($a, 'double');
	echo gettype($a);
?>