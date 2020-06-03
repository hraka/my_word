<?php

$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');


$sql = "
	DELETE FROM word
		WHERE id={$_POST['word_id']};
";


$result = mysqli_query($conn, $sql);
if($result === false){
	echo '삭제하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
	error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
	echo '성공했습니다. <a href="index.php">돌아가기</a>';
}
echo $sql;


/*
unlink('data/'.$_POST['word_name']);
header('Location: /index.php');
*/
?>