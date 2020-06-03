<?php


$conn = mysqli_connect(
    'localhost',
    'root', 
    '1111', 
    'words');


$filtered = array(
    'id' => mysqli_real_escape_string($conn, $_POST['meaning_id']),
    'meaning' => mysqli_real_escape_string($conn, $_POST['new_meaning'])
);



$sql = "
    UPDATE meaning
        SET meaning='{$filtered['meaning']}',
            edited=NOW()
        WHERE id={$filtered['id']};
    ";

$result = mysqli_query($conn, $sql);



$sql_for_word_id = "
    SELECT * FROM meaning WHERE id={$filtered['id']}
    ";

$result_for_word_id = mysqli_query($conn, $sql_for_word_id);
$row_for_word_id = mysqli_fetch_array($result_for_word_id);

if($result === false){
    echo '수정하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
    error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
    echo '성공했습니다. <a href="index.php?word='.$row_for_word_id['word_id'].'">돌아가기</a>';
}
echo $sql;

?>