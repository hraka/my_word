<?php

$conn = mysqli_connect(
    'localhost',
    'root', 
    '1111', 
    'words');

 $filtered = array(
    'm_id'        => mysqli_real_escape_string($conn, $_POST['m_id']),
    'category_id' => mysqli_real_escape_string($conn, $_POST['category_id']),
    'word_id'     => mysqli_real_escape_string($conn, $_POST['word_id'])

);

$sql = "UPDATE meaning
   			SET onoff = 0
   			WHERE id = {$filtered['m_id']}";
echo ($sql);
$result = mysqli_query($conn, $sql);
if($result === false){
	echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
	error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
	echo "성공했습니다. <a href=\"index.php?category={$filtered['category_id']}&word={$filtered['word_id']}\">돌아가기</a>";
	//header("Location : index.php");

}

?>