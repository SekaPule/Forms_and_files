<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";

	$pdo = new PDO("mysql:host=$hostname; dbname=test_db",$username ,$password);


	$query2= $pdo->query('SELECT * FROM `uploaded_text` /*WHERE `text_id`=1*/');

	$ID =1;

	while($row2 = $query2->fetch(PDO::FETCH_OBJ)){

		echo '<hr>'.$row2->id."|".$row2->content."|".$row2->date_of_insert."|".$row2->words_count.' слов</br>';
		$query= $pdo->query('SELECT * FROM `word` WHERE `text_id`= '.$row2->id);
		while($row = $query->fetch(PDO::FETCH_OBJ)){
		    echo $row->id."|".$row->text_id."|".$row->word." - ".$row->count.' cлов</br>';
		}
		$ID++;
	}


?>