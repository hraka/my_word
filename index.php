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
$selected_category = '';
$list = '';
$printing_meanings = '';
$printing_synonyms = '';


$sql_category_list = "SELECT id, category_name FROM category LIMIT 1000";
$result_category_list = mysqli_query($conn, $sql_category_list);

while($row_category_list = mysqli_fetch_array($result_category_list)) {
	$escaped_category = htmlspecialchars($row_category_list['category_name']);
	$category_list = $category_list."<li><a href=\"index.php?category={$row_category_list['id']}\">{$escaped_category}</a></li>";
}

$sql_word_list = "SELECT id, word_name FROM word ORDER BY word_name LIMIT 1000";
$filtered_category_id = 0;

if(isset($_GET['category']) && $_GET['category'] > 0) {
	$filtered_category_id = mysqli_real_escape_string($conn, $_GET['category']);
	$sql_word_list = "SELECT id, word_name FROM categorizing LEFT JOIN word ON categorizing.word_id = word.id WHERE category_id ={$filtered_category_id} ORDER BY word_name"; //*보다 word_name 으로 컬럼명 특정하는게 나을까? 어차피 category_id를 색인하느라 여러 컬럼을 다 읽는 거 아닐까? 가져오는 건 또 다른가? 어디에 쓰는 것도 아닌데도? fetch과정에서 다른가? result에 들어가는 field count 값이 다르다.

	$sql_category = "SELECT * FROM category WHERE id = {$filtered_category_id}";
	$result_category = mysqli_query($conn, $sql_category);
	$row_category = mysqli_fetch_array($result_category);
	$selected_category = ': '.htmlspecialchars($row_category['category_name']);
}

$result_word_list = mysqli_query($conn, $sql_word_list);

while($row_word_list = mysqli_fetch_array($result_word_list)) {
	$escaped_title = htmlspecialchars($row_word_list['word_name']);
	$list = $list."<li><a href=\"index.php?category={$filtered_category_id}&word={$row_word_list['id']}\">{$escaped_title}</a></li>";
}

/*function testfun2($m_id)
{
   echo "Your test function on button click is working";
}

if(array_key_exists('on_off_work',$_POST)){
   testfun2($_POST['on_off_work']);
}*/

if(isset($_GET['word'])) { //word id를 받는다.
	$filtered_word_id = mysqli_real_escape_string($conn, $_GET['word']); //word_info와 통합시킬까?ㄴ
	$sql_word = "SELECT * FROM word WHERE id=\"{$filtered_word_id}\"";
	$result_word = mysqli_query($conn, $sql_word);
	$row_word = mysqli_fetch_array($result_word);	
	$word_info['word_name'] = htmlspecialchars($row_word['word_name']);
	$word_info['profile'] = htmlspecialchars($row_word['profile']);

	$sql_synonym = "SELECT id, word_name FROM relation_synonym LEFT JOIN word ON relation_synonym.obj_word_id = word.id WHERE sub_word_id =\"{$filtered_word_id}\"";
	$result_synonym = mysqli_query($conn, $sql_synonym);
	while ($row_synonym = mysqli_fetch_array($result_synonym)) {
		$escaped_synonym = htmlspecialchars($row_synonym['word_name']);
		$escaped_synonym_id = htmlspecialchars($row_synonym['id']);
		$printing_synonyms = $printing_synonyms." <a href=\"index.php?category={$filtered_category_id}&word={$escaped_synonym_id}\">[".$escaped_synonym."]</a>";
	}


	$sql_meaning = "SELECT * FROM meaning WHERE word_id={$filtered_word_id} ORDER BY created DESC";
	$result_meaning = mysqli_query($conn, $sql_meaning);

	while($row_meaning = mysqli_fetch_array($result_meaning)) {
		$escaped_meaning = nl2br(htmlspecialchars($row_meaning['meaning']));
		$escaped_meaning_id = htmlspecialchars($row_meaning['id']);
		$escaped_time = htmlspecialchars($row_meaning['created']);
		$escaped_source = '';
		$escaped_onoff = htmlspecialchars($row_meaning['onoff']);
		if($row_meaning['source'] !== NULL) {
			$escaped_source = '출처 : '.htmlspecialchars($row_meaning['source']);
		}

		$printing_off_class = '';
		$printing_checked = 'checked';

		var_dump($escaped_onoff);
		if($escaped_onoff == '0') {
			$printing_off_class = "class=\"off\"";
			$printing_checked = "";
		}

		echo ($printing_off_class);

		$printing_meanings = $printing_meanings."
			<article id=\"meaning_{$escaped_meaning_id}\" {$printing_off_class}>
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
			<h1><a href="index.php">언어 사전 </a> <?=$selected_category?></h1>
		</header>	
		<div id="content">
			<nav id="categories">
				<label><strong>카테고리</strong>
					<ul class="category_list">
						<?=$category_list?>
					</ul>
					<input type="button" class="btn" value="카테고리 만들기" onclick = "location.href = 'create_category.php'">

					<ul class="word_list">
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

                유의어 : <?=$printing_synonyms?>

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

