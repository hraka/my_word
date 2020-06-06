
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
                        	<label for="source"> 출처: </label>
                        	<input type="text" name="source" id="source" placeholder="출처가 있는 경우 적어주세요">
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



