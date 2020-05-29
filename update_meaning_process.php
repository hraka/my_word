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
if($result === false){
    echo '수정하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
    error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
    echo '성공했습니다. <a href="index.php">돌아가기</a>';
}
echo $sql;


// function get_valid_file() {
//     if(isset($_POST['word_name'])){
//         return "./data/".$_POST['word_name'];
//     } else {
//         return null;
//     }
// }

// function check_and_get_meaning($filename){
//     if (file_exists($filename)) {
//         if (is_readable($filename)) {
//             return get_meaning_list_from_file($filename);
//         } else {
//             return null;
//         }
//     } else {
//         return null;
//     }
// }

// function get_meaning_list_from_file($filename){
//     $opfile = fopen($filename, 'r');
//     $contents = fread($opfile, filesize($filename));
//     $meaning_explode = explode("+", $contents);
//     fclose($opfile);
//     return $meaning_explode;

// }

// function update_target_meaning_in_file($meaning_list, $target) {

// 	$update_meaning = array($target => $_POST['new_meaning']);

// 	$new_meaning_list = array_replace($meaning_list, $update_meaning);

//     return $new_meaning_list;
// }


// $filename = get_valid_file();
// $target = $_POST['meaning_num'];

// $old_meaning_list = check_and_get_meaning($filename);
// $new_meaning_list = update_target_meaning_in_file($old_meaning_list, $target);

// file_put_contents($filename, implode('+', $new_meaning_list));
// header('Location: /index.php?word='.$_POST['word_name']);



?>