
<?php
$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');

$word_id = $_POST['word_id'];
$sql = "SELECT * FROM word WHERE id={$word_id};";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

$filtered = array(
	'word_name' => htmlspecialchars($row['word_name']),
	'profile' => htmlspecialchars($row['profile'])
);

// $sql = "SELECT * FROM categorizing WHERE word_id = {$word_id}";


// $category_list = "<option value='NULL'> 전체 </option>";
// $sql = "SELECT id, category_name FROM category";
// $result = mysqli_query($conn, $sql);
// while ($row = mysqli_fetch_array($result)) {
// 	$escaped_category_id = htmlspecialchars($row['id']);
// 	$escaped_category_name = htmlspecialchars($row['category_name']);
// 	$category_list = $category_list."
// 		<option value='{$escaped_category_id}'> {$escaped_category_name} </option>";
// }

$world_list = "<option value='NULL'> 전체 </option>";
$sql = "SELECT id, world_name FROM world";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_array($result)) {
	$escaped_world_id = htmlspecialchars($row['id']);
	$escaped_world_name = htmlspecialchars($row['world_name']);
	$world_list = $world_list."
		<option value='{$escaped_world_id}'> {$escaped_world_name} </option>";
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
	</head>

	<body>
		<div>
		
			<h1><a href="index.php">메인 메뉴 관련 내용 작게</a></h1>
			
			
			<div>

				<div id="set">
				
				    
				
					<h2>
                        수정
                    </h2>



                        
                    
                    <form action="update_word_process.php" method="post">
                       
                        <p>
 	                       <input type="text" name="word_name" placeholder="단어" value="<?=$filtered['word_name']?>">
 	                       <input type="hidden" name="word_id" placeholder="단어" value="<?=$_POST['word_id']?>">
                     	</p>
                     	<p>
                        	<textarea name="profile" placeholder="설명"><?=$filtered['profile']?></textarea>
                        </p>

                        <p class="relation">
                        	<label for="synonym"> 유의어 </label>
                        		<input type="text" name="synonym" id="synonym">
                        	
                        </p>
                        <p class="relation">
                        	<label for="antonym"> 반의어 </label>
                        		<input type="text" name="antonym" id="antonym">
                        	
                        </p>

                        <p>
                        	<select name="world">
                        	<?=$world_list?>
                        	</select>
                        </p>

                        <p>	
                        	<input type="submit" value="수정하기" class="btn">
                        </p>
                    </form>


					
				</div>

			</div>
		</div>

	</body>

</html>


