<?php

$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');

$category_list = ''; 

$sql_category_list = "SELECT id, category_name FROM category LIMIT 1000";

echo $sql_category_list;
$result_category_list = mysqli_query($conn, $sql_category_list);

while($row_category_list = mysqli_fetch_array($result_category_list)) 
{
	$filtered_category_id = htmlspecialchars($row_category_list['id']);
	$filtered_category = htmlspecialchars($row_category_list['category_name']);

	$category_list = $category_list.
		"<li><a href=\"category_set.php?category={$filtered_category_id}\">
			{$filtered_category}</a></li>";
}


$selected_category_id = 0;
$selected_word_list = '';

if(isset($_GET['category'])) {
	$selected_category_id = $_GET['category'];
}

if($selected_category_id > 0) {
	$sql_selected_list = "
		SELECT id, word_name, category_id
		FROM word
		LEFT JOIN categorizing 
		ON word.id = categorizing.word_id
		ORDER BY word_name
		LIMIT 1000
		";

	echo $sql_selected_list;

	$result_selected_list = mysqli_query($conn, $sql_selected_list);

	while ($row_selected_list = mysqli_fetch_array($result_selected_list)) {

		$filtered_word_id = htmlspecialchars($row_selected_list['id']);
		$filtered_word = htmlspecialchars($row_selected_list['word_name']);
		$printing_checked = "";

		if ($selected_category_id === $row_selected_list['category_id']) {
			$printing_checked = "checked";
		}

		$selected_word_list = $selected_word_list."
			<input type='checkbox' id='{$filtered_word_id}' value='{$filtered_word}' {$printing_checked}>
			<label for='{$filtered_word_id}'>{$filtered_word}</label> <br>
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
			<h1><a href="index.php">언어 사전</a></h1>
			<h2>카테고리 설정</h2>

		</header>	
		<div id="content">
			<div id="left_content">
				<strong>카테고리</strong>
				<ul>
					<?=$category_list?>
				</ul>

				<input type="button" class="btn" value="카테고리 만들기" onclick = "location.href = 'create_category.php'">


			</div>
			<div id="set">
					
				<?=$selected_word_list?>

			</div>
		</div>
		
	</body>
</html> 