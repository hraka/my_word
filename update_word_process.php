<?php


$conn = mysqli_connect(
    'localhost',
    'root', 
    '1111', 
    'words');

$filtered = array(
    'id'        => mysqli_real_escape_string($conn, $_POST['word_id']),
    'word_name' => mysqli_real_escape_string($conn, $_POST['word_name']),
    'profile'   => mysqli_real_escape_string($conn, $_POST['profile']),
    'synonym'   => mysqli_real_escape_string($conn, preg_replace("/\s+/", "", $_POST['synonym'])),
    'world'  => mysqli_real_escape_string($conn, $_POST['world'])
);

$synonyms = explode(",", $filtered['synonym'] );
$i = 0;

echo count($synonyms);
print_r($synonyms);

$add_world = '';
if($filtered['world'] != 'NULL') {
    echo '이써';
    $add_world = ", world = '".$filtered['world']."'";
} else {
    echo '없써';
    $add_world = ", world = NULL";
}

if($synonyms[0] != '') {
    while($i < count($synonyms)) {
        //단어 검색
        $sql_search = "
            SELECT id FROM word WHERE word_name = '{$synonyms[$i]}'";
        //단어는 하나 나온다고 가정
        $result_search  = mysqli_query($conn, $sql_search);
        $row = mysqli_fetch_array($result_search);

        echo $sql_search;

        if($row['id'] == NULL) {
            echo "유의어로 호출된 단어가 검색되지 않습니다.";
        } else {
            $sql_synonym = "
                INSERT INTO relation_synonym
                    (sub_word_id, obj_word_id, created)
                    VALUES ({$filtered['id']}, {$row['id']}, NOW());
                    ";

            $result_synonym = mysqli_query($conn, $sql_synonym);

            if($result_synonym === false){
                echo '유의어를 입력하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것 겁내 이상하네 실패해도 안뜬다....
                error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
            } else {
                
                echo '유의어 입력에 성공했습니다.';
            }

            echo $sql_synonym;
        }

        $i = $i + 1;

    }
}


$sql = "
    UPDATE word
        SET word_name='{$filtered['word_name']}', 
        profile='{$filtered['profile']}'".
        $add_world.
        " 
        WHERE id={$filtered['id']};
    ";
//id는 동일한채 word 표현형을 바꾼다? 애매하지 않을까? ... 사람들이 word id를 바꿀 수 있기는 할까? 차라리 meaning들에게 부여된 id를 바꾸는 편이 나을 수도 있다.

echo $sql;

$result = mysqli_query($conn, $sql);
if($result === false){
    echo '수정하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
    error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
} else {
    Header("Location: index.php?word={$filtered['id']}");
}

?>