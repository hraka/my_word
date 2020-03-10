
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
                        
                        <input type="hidden" name="old_name" value="<?=$_GET['word']?>">
                        <p>
 	                       <input type="text" name="word_name" placeholder="단어" value="<?php echo $_GET['word']?>">

                        </p>




                        <p>	
                        	<input type="submit">
                        </p>
                    </form>


					
				</div>

			</div>
		</div>

	</body>
	
	<?php
    

    ?>

</html>



