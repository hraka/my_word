<?php
$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');



$word_info = array(
	'word_name' => 'Welcome',
	'profile' => 'Hello, This is your Dictionary');

$category_list = "<li><a href=\"index.php?\">전체</a></li>";

$sql_category_list = "SELECT id, category_name FROM category LIMIT 1000";
$result_category_list = mysqli_query($conn, $sql_category_list);
while($row_category_list = mysqli_fetch_array($result_category_list)) {
	$escaped_category = htmlspecialchars($row_category_list['category_name']);
	$category_list = $category_list."<li><a href=\"index.php?category={$row_category_list['id']}\">{$escaped_category}</a></li>";
}

$sql_word_list = "SELECT id, word_name FROM word LIMIT 1000";
$filtered_category_id = 0;

if(isset($_GET['category']) && $_GET['category'] > 0) {
	$filtered_category_id = mysqli_real_escape_string($conn, $_GET['category']);
	$sql_word_list = "SELECT id, word_name FROM categorizing LEFT JOIN word ON categorizing.word_id = word.id WHERE category_id ={$filtered_category_id}"; //*보다 word_name 으로 컬럼명 특정하는게 나을까? 어차피 category_id를 색인하느라 여러 컬럼을 다 읽는 거 아닐까? 가져오는 건 또 다른가? 어디에 쓰는 것도 아닌데도? fetch과정에서 다른가? result에 들어가는 field count 값이 다르다.
}

$result_word_list = mysqli_query($conn, $sql_word_list);

$list = '';
while($row_word_list = mysqli_fetch_array($result_word_list)) {
	$escaped_title = htmlspecialchars($row_word_list['word_name']); //목록에 들어갈 단어명
	$list = $list."<li><a href=\"index.php?category={$filtered_category_id}&word={$row_word_list['id']}\">{$escaped_title}</a></li>"; //단어 id를 GET으로 취하는 url 연결
}



$printing_meanings = '';

if(isset($_GET['word'])) { //word id를 받는다.
	$filtered_word_id = mysqli_real_escape_string($conn, $_GET['word']); //word_info와 통합시킬까?ㄴ
	$sql_word = "SELECT * FROM word WHERE id=\"{$filtered_word_id}\"";
	$result_word = mysqli_query($conn, $sql_word);

	$row_word = mysqli_fetch_array($result_word);
	
	$word_info['word_name'] = htmlspecialchars($row_word['word_name']);
	$word_info['profile'] = htmlspecialchars($row_word['profile']);


//	$sql_synonym = "SELECT * FROM relation_synonym WHERE sub_id =\"{$filtered_word_id}\"";


	$sql_meaning = "SELECT * FROM meaning WHERE word_id={$filtered_word_id}";
	$result_meaning = mysqli_query($conn, $sql_meaning);

	while($row_meaning = mysqli_fetch_array($result_meaning)) {

	
		$escaped_meaning = nl2br(htmlspecialchars($row_meaning['meaning']));
		$escaped_meaning_id = htmlspecialchars($row_meaning['id']);
		$escaped_time = htmlspecialchars($row_meaning['created']);


		$printing_meanings = $printing_meanings."
			<article>
				{$escaped_meaning}

				<div class=\"bottom_of_meaning right\">
					{$escaped_time}
					<form action=\"update_meaning.php\" method=\"post\" class=\"btn\">
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
				</div>
			</article>
		";
	}
}
?>

<!doctype html>
<html>
	<head>
		<title>언어 사전</title>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="switch.css">
		<link href="https://fonts.googleapis.com/css?family=Nanum+Myeongjo|Noto+Sans+KR&display=swap" rel="stylesheet">
	</head>
	<body>
		<header>
			<h1><a href="index.php">언어 사전</a></h1>
		</header>	
		<div id="content">
			<nav id="categories">
				<label><strong>카테고리 <?=$category_list?></strong>
					<ul>
						<?=$list?>
					</ul>
				</label>
				<input type="button" class="btn" name="만들기" value="만들기" onclick = "location.href = 'create.html'">
			</nav>
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

				<form action="create_meaning.php" method="post" class="right">
	           		<input type="hidden" name="word_id" value="<?=$filtered_word_id?>">
	           		<input type="hidden" name="word_name" value="<?=$word_info['word_name']?>">
	           		<input type="submit" value="만들기" class="btn">
	           	</form>
                   	<?php
                   } else {
                   	?>
               	</div>                  
                   <?php
               		}
                   ?>
                   <?=$printing_meanings?>
			</div>
		</div>
	</body>
</html> 