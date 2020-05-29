
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
			
			
			<div>

				<div id="set">
				
				    
				
					<h2>
                        <?php
                        echo ($_POST['word_name']);
                        ?>
                    </h2>

                    
                    
                    <form action="create_meaning_process.php" method="post">
                        <p>
                        	<input type="hidden" name="word_id" value="<?=$_POST['word_id']?>">
                        	<textarea name="meaning" placeholder="새로운 뜻"></textarea>
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



