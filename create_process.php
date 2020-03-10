<?php
file_put_contents('data/'.$_POST['word_name'], '+'.$_POST['meaning']);
header('Location: /index.php?word='.$_POST['word_name']);
?>