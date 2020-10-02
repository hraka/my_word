<?php

function connect_to_mysql(){}
$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');


$word_info = array(
	'word_name' => '전체 단어 보기',
	'profile' => 'Hello, This is your Dictionary');

$category_list = "<li><a href=\"index.php?\">전체</a></li>";
$selected_category = '';
$list = '';
$printing_meanings = '';
$printing_synonyms = '';
$card_list = '';



$printing_world = '';

function get_world_list($conn) {

	$sql = "SELECT id, world_name FROM world";
	$result = mysqli_query($conn, $sql);
	while($row = mysqli_fetch_array($result)) {
		$escaped_world = htmlspecialchars($row['world_name']);
		$escaped_world_id = htmlspecialchars($row['id']);
		$printing_world = $printing_world."
			<li><a href='index.php?world={$escaped_world_id}'>{$escaped_world}</a></li>";
	}
	return $printing_world;
}
$printing_world = get_world_list($conn);



function get_category_list($conn) {

	$sql_category_list = "SELECT id, category_name FROM category LIMIT 1000";
	$result_category_list = mysqli_query($conn, $sql_category_list);

	while($row_category_list = mysqli_fetch_array($result_category_list)) {
		$escaped_category = htmlspecialchars($row_category_list['category_name']);
		$escaped_category_id = htmlspecialchars($row_category_list['id']);
		$category_list = $category_list."<li><a href=\"index.php?category={$escaped_category_id}\">{$escaped_category}</a></li>";
	}

	return $category_list;
}
$category_list = get_category_list($conn);

// function Make_List($result, $attribute1, $attribute2, $text) {
// 	while($row = mysqli_fetch_array($result)) {
// 		$escaped_attribute1 = htmlspecialchars($row[$attribute1]);
// 		$escaped_attribute2 = htmlspecialchars($row[$attribute2]);
// 		$list = $list.
// 	}
// }

$show_num = 1000;

$sql_word_list = "SELECT id, word_name FROM word ORDER BY word_name";
$filtered_category_id = 0;

if(isset($_GET['world']) && $_GET['world'] > 0) {
	$filtered_world_id = mysqli_real_escape_string($conn, $_GET['world']);
	$sql_word_list = "SELECT DISTINCT word.id, word_name FROM meaning LEFT JOIN word ON meaning.word_id = word.id WHERE world_id = {$filtered_world_id}";

	$word_info['word_name'] = "세계 선택";
	$word_info['profile'] = "세계를 선택했습니다";

} 
else if(isset($_GET['category']) && $_GET['category'] > 0) {
	$filtered_category_id = mysqli_real_escape_string($conn, $_GET['category']);
	$sql_word_list = "SELECT id, word_name FROM categorizing LEFT JOIN word ON categorizing.word_id = word.id WHERE category_id ={$filtered_category_id} ORDER BY word_name"; //

	$sql_category = "SELECT * FROM category WHERE id = {$filtered_category_id}";
	$result_category = mysqli_query($conn, $sql_category);
	$row_category = mysqli_fetch_array($result_category);
	$selected_category = htmlspecialchars($row_category['category_name']);

	$word_info['word_name'] = $selected_category.' 카테고리';
	$word_info['profile'] = '카테고리를 선택했습니다';


}
else if(isset($_GET['search'])){
	$filtered_search = mysqli_real_escape_string($conn, $_GET['search']);
	$search_keyword = $filtered_search;
	$sql_word_list = "SELECT id, word_name FROM word WHERE word_name LIKE '%{$search_keyword}%'";

	$word_info['word_name'] = $search_keyword.' 검색 결과';
	$word_info['profile'] = '검색 결과 입니다.';
}

$result_word_list = mysqli_query($conn, $sql_word_list." LIMIT {$show_num}");
$url_now = htmlspecialchars($_SERVER['QUERY_STRING']);
//$url_now2 = $_SERVER['REQUEST_URI'];
echo $url_now;


while($row_word_list = mysqli_fetch_array($result_word_list)) {
	$escaped_word = htmlspecialchars($row_word_list['word_name']);
	$filtered_word_id = htmlspecialchars($row_word_list['id']);

	$list = $list."<li><a href=\"index.php?".$url_now."&word={$filtered_word_id}\">{$escaped_word}</a></li>";
}

$card_list = show_word_card_in_main($sql_word_list, $conn, 15);



function show_word_card_in_main($sql, $conn, $show_num){
	// $sql = "SELECT id, word_name FROM word ORDER BY id DESC LIMIT {$show_num}";

	// if($filtered_category_id > 0) {
	// 	$sql = "SELECT id, word_name FROM categorizing LEFT JOIN word ON categorizing.word_id = word.id WHERE category_id ={$filtered_category_id} ORDER BY id DESC LIMIT {$show_num}";
	// }

	$result = mysqli_query($conn, $sql." LIMIT {$show_num}");
	$card_list = '';

	$url_now = htmlspecialchars($_SERVER['QUERY_STRING']);
	while($row = mysqli_fetch_array($result)) {
		$escaped_word = htmlspecialchars($row['word_name']);
		$filtered_word_id = htmlspecialchars($row['id']);

		$card_list = $card_list."
			<a href='index.php?".$url_now."&word={$filtered_word_id}'><div class='meaning'>
				<form method=\"post\" class=\"on_off\" action=\"\">
						<label class=\"switch\">
							<input type=\"checkbox\" name=\"on_off\" id=\"on_off\"value=\"눌렸어\"onclick=\"\" onChange=\"this.form.submit()\">
							<span class=\"slider round\"></span>
							<input type=\"hidden\" name=\"m_id\" value={$escaped_meaning_id}>
							<input type=\"hidden\" name=\"category_id\" value={$filtered_category_id}>
							<input type=\"hidden\" name=\"word_id\" value={$filtered_word_id}>
							<input type=\"hidden\" name=\"now_onoff\" value={$escaped_onoff}>
						</label>
					</form>
				{$escaped_word}
			</div></a>";
	}

	return $card_list;
}




function show_synonym_list($filtered_word_id, $conn) {
	$printing_synonyms = '';
	$sql_synonym = "SELECT id, word_name FROM relation_synonym LEFT JOIN word ON relation_synonym.obj_word_id = word.id WHERE sub_word_id =\"{$filtered_word_id}\"";
	$result_synonym = mysqli_query($conn, $sql_synonym);
	while ($row_synonym = mysqli_fetch_array($result_synonym)) {
		$escaped_synonym = htmlspecialchars($row_synonym['word_name']);
		$escaped_synonym_id = htmlspecialchars($row_synonym['id']);
		$printing_synonyms = $printing_synonyms." <a href=\"index.php?category={$filtered_category_id}&word={$escaped_synonym_id}\">[".$escaped_synonym."]</a>";
	}
	return $printing_synonyms;
}

function show_meaning_card($filtered_word_id, $conn){
	$printing_meanings = '';
	$sql = "SELECT * FROM meaning WHERE word_id={$filtered_word_id} ORDER BY created DESC";
	$result = mysqli_query($conn, $sql);

	while($row = mysqli_fetch_array($result)) {
		$escaped_meaning = nl2br(htmlspecialchars($row['meaning']));
		$escaped_meaning_id = htmlspecialchars($row['id']);
		$escaped_time = htmlspecialchars($row['created']);
		$escaped_source = '';
		$escaped_onoff = htmlspecialchars($row['onoff']);
		if($row['source'] != NULL) { //!==에서는 row['source']가 ''일 때도 출처가 표기됨  
			$escaped_source = '출처 : '.htmlspecialchars($row['source']);
		}

		$printing_off_class = '';
		$printing_checked = 'checked';

		if($escaped_onoff == '0') {
			$printing_off_class = "class=\"off\"";
			$printing_checked = "";
		}

		$printing_meanings = $printing_meanings."
			<div class=\"meaning\" id=\"meaning_{$escaped_meaning_id}\" {$printing_off_class}>
				<form method=\"post\" class=\"on_off\" action=\"checked_process.php\">
					<label class=\"switch\">
						<input type=\"checkbox\" name=\"on_off\" id=\"on_off\"value=\"눌렸어\"onclick=\" button_click('{$escaped_meaning_id}');\" onChange=\"this.form.submit()\" {$printing_checked}>
						<span class=\"slider round\"></span>
						<input type=\"hidden\" name=\"m_id\" value={$escaped_meaning_id}>
						<input type=\"hidden\" name=\"category_id\" value={$filtered_category_id}>
						<input type=\"hidden\" name=\"word_id\" value={$filtered_word_id}>
						<input type=\"hidden\" name=\"now_onoff\" value={$escaped_onoff}>
					</label>
				</form>

				<div class=\"middle_of_meaning\">
					{$escaped_meaning}
				</div>

				<div class=\"bottom_of_meaning right\">
					<div class=\"source\"><a class=\"source\" href=\"{$escaped_source}\">{$escaped_source}</a></div>
					{$escaped_time}
							<span> <form action=\"update_meaning.php\" method=\"post\" class=\"btn\">
								<input type=\"hidden\" name=\"meaning_id\" value=\"{$escaped_meaning_id}\">
								<input type=\"hidden\" name=\"old_meaning\" value=\"{$escaped_meaning}\">
								<input type=\"hidden\" name=\"word_name\" value=\"{$word_info['word_name']}\">
								<input type=\"submit\" value=\"수정하기\" class=\"btn\">
							</form>

							<form action=\"delete_meaning_process.php\" method=\"post\" class=\"btn\">
								<input type=\"hidden\" name=\"meaning_id\" value=\"{$escaped_meaning_id}\">
								<input type=\"hidden\" name=\"word_id\" value=\"{$filtered_word_id}\">
								<input type=\"submit\" value=\"삭제하기\" class=\"btn\">
							</form>
					</span>
				</div>
			</div>
		";
	}
	return $printing_meanings;
}

function get_word_card_each(){}

if(isset($_GET['word'])) { //word id를 받는다.
	$filtered_word_id = mysqli_real_escape_string($conn, $_GET['word']); //word_info와 통합시킬까?ㄴ
	$add_world = "";

	$sql_word = "SELECT word.id, word_name, profile, world_name FROM word LEFT JOIN world ON word.world = world.id WHERE word.id=\"{$filtered_word_id}\"";
	$result_word = mysqli_query($conn, $sql_word);
	$row_word = mysqli_fetch_array($result_word);
	$filtered_word_world_name = htmlspecialchars($row_word['world_name']);	
	if($filtered_word_world_name != NULL) {
		$add_world = "[{$filtered_word_world_name}]세계의 단어 <br>";
	}
	$word_info['word_name'] = htmlspecialchars($row_word['word_name']);
	$word_info['profile'] = $add_world.htmlspecialchars($row_word['profile']);

	$printing_synonyms = show_synonym_list($filtered_word_id, $conn);
	$printing_meanings = show_meaning_card($filtered_word_id, $conn);
}


$world_list = "<option value='NULL'> 전체 </option>";
function select_world_list($conn){
	$sql = "SELECT id, world_name FROM world";
	$result = mysqli_query($conn, $sql);
	while ($row = mysqli_fetch_array($result)) {
		$escaped_world_id = htmlspecialchars($row['id']);
		$escaped_world_name = htmlspecialchars($row['world_name']);
		$world_list = $world_list."
			<option value='{$escaped_world_id}'> {$escaped_world_name} </option>";
	}

	$result = mysqli_query($conn, $sql);
	if($result === false){
		echo '저장하는 과정에서 문제가 생겼습니다. 관리자에게 문의해주세요.'; //사용자에게 뜨는 것
		error_log(mysqli_error($conn)); //관리자가 볼 수 있는 시스템 에러 메세지.
	} else {
		//echo '성공했습니다.';
	}

	return $world_list;
}
$world_list = select_world_list($conn);







?>

<!doctype html>
<html>
	<head>
		<title>언어 사전</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">	
		<link rel="stylesheet" href="switch.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nanum+Myeongjo|Noto+Sans+KR&display=swap" >
		<link rel="stylesheet" href="style_mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script>
			function button_click(m_id) {
				var instance = document.getElementById("meaning_"+ m_id);
				instance.classList.toggle('off');
				alert ("meaning_" + m_id + "버튼을 누르셨습니다.");
			}
		</script>
	</head>
	<body>
		<header>
			<div class="header_bar">
				안녕하세요

				<form>
					<select name="selected_world">
						<?=$world_list?>
					</select>
				</form>
			</div>
			<h1><a href="index.php">언어 사전</a></h1>
		</header>	
		<div id="content">
			<div id="left_content">

				<form action="index.php" method="get">
					<input type="text" name="search">
					<input type="submit" name="" value="검색">
				</form>

				<ul>
					<?=$printing_world?>
				</ul>

				<input type="button" class="btn" value="세계 만들기" onclick = "location.href = 'create_world.php'">

				<nav id="categories">
					<button onclick="
						document.getElementById('categories').style.display='none';
						document.getElementById('open_category_btn').style.display='block';
						">
						카테고리 접기
					</button>
					<br>
					<label><strong>카테고리</strong>
						<ul class="category_list">
							<?=$category_list?>
						</ul>
						<input type="button" class="btn" value="카테고리 설정" onclick = "location.href = 'category_set.php'">

						<form>
							<input type="hidden" name="world" value="<?=$_POST['world']?>">
							<input type="submit" value="단어 만들기">
						</form>

						<input type="button" class="btn" name="만들기" value="단어 만들기" onclick = "location.href = 'create.html'">

						<ul class="word_list">
							<?=$list?>
						</ul>
					</label>
				</nav>
				<button id="open_category_btn" style="display: none" onclick="
					document.getElementById('categories').style.display='block';
					this.style.display='none';
				">
					카테고리 펼치기
				</button>
			</div>
			<div id="set">
						    
				<div id="word">
					<h2>
	 					<?=$word_info['word_name']?> 
                    </h2>
                    <strong>                    	
                    	<?=$word_info['profile']?>
                    </strong>
                    
                    <?php
                    if(isset($_GET['word'])){
                    	?>            
                    <div class="right"> 
	                   	<form action="update_word.php" method="post" class="btn">
	                   		<input type="hidden" name="word_id" value="<?=$filtered_word_id?>">
	                   		<input type="hidden" name="word_name" value="<?=$word_info['word_name']?>">
	                   		<input type="submit" value="수정하기" class="btn">
	                   	</form>
	                   	<form action="delete_process.php" method="post" class="btn">
	                   		<input type="hidden" name="word_id" value="<?=$filtered_word_id?>">
	                   		<input type="submit" value="삭제하기" class="btn">
	                   	</form>
	                </div>
                </div>

                유의어 <?=$printing_synonyms?>

				<form action="create_meaning.php" method="post" class="right">
	           		<input type="hidden" name="word_id" value="<?=$filtered_word_id?>">
	           		<input type="hidden" name="word_name" value="<?=$word_info['word_name']?>">
	           		<input type="submit" value="뜻 만들기" class="btn">
	           	</form>
                   	<?php
                   } else {
                   	?>
               	</div>
               		<?php
               			if(!isset($_GET['word'])) {
               				echo ($card_list);
               			}
               		?>                
                   <?php
               		}
                   ?>
                   <?=$printing_meanings?>
			</div>
		</div>
		
	</body>
</html> 

