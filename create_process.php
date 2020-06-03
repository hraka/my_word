<?php


$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');


$filtered = array(
	'word_name' => mysqli_real_escape_string($conn, $_POST['word_name']),
	//'meaning' => mysqli_real_escape_string($conn, $_POST['meaning'])
);


$sql = "
	INSERT INTO word
		(word_name, created)
		VALUES(
			'{$filtered['word_name']}',
			NOW()
		)
	";
$result = mysqli_query($conn, $sql);

$last_uid = mysqli_insert_id($conn);
// var_dump($last_uid);
// 	$last_uid2 = mysqli_query($conn, "select last_insert_id()");
// 	var_dump($last_uid2);
// 	$lala = mysqli_fetch_array($last_uid2);
// 	print_r($lala);


if($result === false){
	echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
	error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
	echo '성공했습니다. <a href="index.php?word='.$last_uid.'">돌아가기</a>';
}
echo $sql;
// file_put_contents('data/'.$_POST['word_name'], $_POST['meaning']);
// header('Location: /index.php?word='.$_POST['word_name']);
?>