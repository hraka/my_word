
<?php
$conn = mysqli_connect(
	'localhost',
	'root', 
	'1111', 
	'words');




$sql = "SELECT * FROM word WHERE id={$_POST['word_id']};";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

print_r($row);


$filtered = array(
	'word_name' => htmlspecialchars($row['word_name']),
	'profile' => htmlspecialchars($row['profile'])
);

print_r($filtered);

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
		
			<h1><a href="index.php">메인 메뉴 관련 내용 작게</a></h1>
			
			
			<div id="content">

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
                        	<textarea name="profile"> <?=$filtered['profile']?>	</textarea>
                        </p>




                        <p>	
                        	<input type="submit" value="수정하기">
                        </p>
                    </form>


					
				</div>

			</div>
		</div>

	</body>

</html>


