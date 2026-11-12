<?php  

    echo 'test date'."\n";
    echo DateTime::createFromFormat('Y-m-d H:i:s',"2019-10-13 23:00:25")->getTimestamp()."\n"; 

?>