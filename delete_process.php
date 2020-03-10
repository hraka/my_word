<?php

unlink('data/'.$_POST['word_name']);
header('Location: /index.php');

?>