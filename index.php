<?php

function print_word(){
    if(isset($_GET['word'])) {
        echo ($_GET['word']);
    } else {
        echo "단어사전 입니다";
    }
}

function print_filelink_list(){
    $list = scandir('./data');
    
    $i = 0;
    while($i < count($list)){

        if($list[$i] != '.' ) {
            if($list[$i] != '..') {
                echo "<li><a href=\"index.php?word=$list[$i]\">$list[$i]</a></li>\n";
            }
        }                            
        $i = $i + 1; 
    }
}


function get_onoff_button(){
	return "
	<div class='on_off'>
		<input type=\"radio\" id=\"off\" name=\"on_off\" value=\"off\">
		<label for=\"off\">끄기</label>
		<input type=\"radio\" id=\"on\" name=\"on_off\" value=\"on\">
		<label for=\"on\">켜기</label>
	</div>";
}

function get_meaning_update_button($i, $meaning){
	return "
	<form action=\"update_meaning.php\" method=\"post\" class=\"btn\">
   		<input type=\"hidden\" name=\"word_name\" value=\"".$_GET['word']."\">
   		<input type=\"hidden\" name=\"meaning_num\" value=\"".$i."\">
   		<input type=\"hidden\" name=\"old_meaning\" value=\"".$meaning."\">
   		<input type=\"submit\" value=\"수정하기\" class=\"btn\">
   	</form>";
}

function print_meaning($meaning_explode){
    if(isset($meaning_explode)) {

        $i = 0;
        while($i < count($meaning_explode)) {                            
            if(trim($meaning_explode[$i]) != "") {
                make_card($meaning_explode[$i], $i);
            }
            $i = $i + 1;
        }
    } else {
        echo "단어를 눌러주세요";
    }

}

function make_card($meaning, $i){
	echo "<div class=\"article\" id=\"article$i\">\n".
	get_onoff_button().
	get_meaning_update_button($i, $meaning).
	"<p>$meaning</p>\n </div>";

}

//메잌 센스 함수: 내용을 넣고 박스를 만들라고 해라.

function get_valid_file() {
    if(isset($_GET['word'])){
        return "./data/".$_GET['word'];
    } else {
        return null;
    }
}


function get_meaning_list_from_file($filename){
    $opfile = fopen($filename, 'r');
    $contents = fread($opfile, filesize($filename));
    $meaning_explode = explode("+", $contents);
    fclose($opfile);
    return $meaning_explode;

}

function check_and_get_meaning($filename){
    if (file_exists($filename)) {
        if (is_readable($filename)) {

            return get_meaning_list_from_file($filename);

        } else {
            echo "읽을 수 없어요";
            return null;
        }
    } else {
        echo "없군요";
        return null;
    }
}

$filename = get_valid_file(); 

?>
<!doctype html>
<html>
	<head>
		<title>언어 사전</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<link href="https://fonts.googleapis.com/css?family=Nanum+Myeongjo|Noto+Sans+KR&display=swap" rel="stylesheet">
	</head>

	<body>
		<div>
		
			<h1><a href="index.php">언어 사전</a></h1>
			
			
			<div id="content">
				<div id="categories">
					<strong>카테고리</strong>
					<ul>
                        <?php
                            print_filelink_list();
                        ?>
					</ul>
					
					<input type="button" class="btn" name="만들기" value="만들기" onclick = "location.href = 'create.html'">
				</div>
				<div id="set">
				
				    <div class="move" id="add">+</div>
				    
					<div id="word">
						<h2>
						<?php
	                        print_word();
	                    ?>
	                    </h2>

	                    <?php
	                    if(isset($_GET['word'])) {

	                    ?>

	                    <a href="update_word.php?word=<?=$_GET['word']?>" class="btn">수정하기</a>
	                   	<form action="delete_process.php" method="post" class="btn">
	                   		<input type="hidden" name="word_name" value="<?=$_GET['word']?>">
	                   		<input type="submit" value="삭제하기" class="btn">
	                   	</form>
	                
	                    <?php
	                    }
	                    ?>

					</div>


					<?php
					if(isset($_GET['word'])) {
					?>

						<form action="create_meaning.php" method="post" class="btn">
	                   		<input type="hidden" name="word_name" value="<?=$_GET['word']?>">
	                   		<input type="submit" value="뜻만들기" class="btn">
	                   	</form>

					<?php
					}
					?>


                    
                    
                    <?php
                    
                    
                    print_meaning(check_and_get_meaning($filename));
                    
                    ?>


                        
                    <?php echo (get_onoff_button()); ?>
					
				</div>

			</div>
		</div>

	</body>
	
	<?php
    

    ?>

</html>



