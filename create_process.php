<?php
file_put_contents('data/'.$_GET['word_name'], '+'.$_GET['meaning']);
header('Location: /index.php?word='.$_GET['word_name']);
?>