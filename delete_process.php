<?php

unlink('data/'.$_GET['word_name']);
header('Location: /index.php');

?>