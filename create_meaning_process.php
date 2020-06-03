<?php

$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');


$filtered = array(
	'word_id' => mysqli_real_escape_string($conn, $_POST['word_id']), //꼭 필요 없을수도? 이미 필터 처리된 내용만 오려나?
	'meaning' => mysqli_real_escape_string($conn, $_POST['meaning'])
);


$sql = "
	INSERT INTO meaning
		(meaning, word_id, author_id, created)
		VALUES(
			'{$filtered['meaning']}',
			'{$filtered['word_id']}',
			'1111',
			NOW()
		)
	";



$result = mysqli_query($conn, $sql);
if($result === false){
	echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
	error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
	echo '성공했습니다. <a href="index.php?word='.$filtered['word_id'].'">돌아가기</a>';
}

echo $sql;


/*
file_put_contents('data/'.$_POST['word_name'], '+'.$_POST['meaning'], FILE_APPEND);
header('Location: /index.php?word='.$_POST['word_name']);
*/
?>

