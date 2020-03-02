<?php
    $fp = fopen('sample.txt', 'a');
    fwrite($fp, $_GET['meaning'].'을 추가했다');
    fclose($fp);

    echo $_GET['meaning'];

?>