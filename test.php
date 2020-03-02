<?php

$filename = 'sample.txt';

if(is_readable($filename)) {
    echo 'you can read';
} else {
    echo 'you cant read';
}

?>