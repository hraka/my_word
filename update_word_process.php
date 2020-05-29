<?php


$conn = mysqli_connect(
    'localhost',
    'root', 
    '1111', 
    'words');


$filtered = array(
    'id' => mysqli_real_escape_string($conn, $_POST['word_id']),
    'word_name' => mysqli_real_escape_string($conn, $_POST['word_name']),
    'profile' => mysqli_real_escape_string($conn, $_POST['profile'])
);



$sql = "
    UPDATE word
        SET word_name='{$filtered['word_name']}', 
        profile='{$filtered['profile']}'
        WHERE id={$filtered['id']};
    ";
//id는 동일한채 word 표현형을 바꾼다? 애매하지 않을까? ... 사람들이 word id를 바꿀 수 있기는 할까? 차라리 meaning들에게 부여된 id를 바꾸는 편이 나을 수도 있다.

$result = mysqli_query($conn, $sql);
if($result === false){
    echo '수정하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
    error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
    echo '성공했습니다. <a href="index.php">돌아가기</a>';
}
echo $sql;
?>