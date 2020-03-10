<?php
rename('data/'.$_POST['old_name'], 'data/'.$_POST['word_name']);
header('Location: /index.php?word='.$_POST['word_name']);
?>