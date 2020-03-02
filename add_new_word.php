<meta charset="utf-8">
       
       <link rel="stylesheet" href="style.css">


<?php

$newfile = fopen('new_sample.txt', 'w+');

fwrite($newfile, $_GET['newword'].'*');

fclose($newfile);

echo $_GET['newword'];


?>


