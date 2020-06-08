<a href="processing.php">Загрузить</a>
<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";
	$array=[];
	$pdo = new PDO("mysql:host=$hostname; dbname=test_db",$username ,$password);

	$query= $pdo->query('SELECT * FROM `uploaded_text`');

	echo "<ul>";
	while($row = $query->fetch(PDO::FETCH_OBJ)) {
	    echo "<li>".$row->id."|".(substr($row->content,0,39))."...|".$row->date_of_insert."|".$row->words_count." слов|".'</li><a href="result.php">Показать</a>';
	}
	echo "</ul>";
?>