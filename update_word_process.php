<?php
rename('data/'.$_GET['old_name'], 'data/'.$_GET['word_name']);
header('Location: /index.php?word='.$_GET['word_name']);
?>