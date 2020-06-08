<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";

	//Подключение БД
	try {
		$pdo = new PDO("mysql:host=$hostname; dbname=test_db",$username ,$password);
		//echo "Connection Succesfully!</br>	";
	} catch (PDOException $e) {
		echo "Connection Failed!".$e->getMessage();
	}

	function word_count($words){
		$count= 0;
		$array_of_string = explode(" ", $words);
		foreach ($array_of_string as $key => $value) {
			$count++;
		}
		return $count;
	}

	function text_handling($input_string){

		//Разбиваем текст по словам на массив строк
		$input_string = explode(" ", $input_string);

		//Перевод слов текста в нижний регистр
		//удаление лишних знаков пунктуации
		for ($i=0; $i < count($input_string) ; $i++) {
			$input_string[$i] = mb_strtolower("$input_string[$i]");
			if (in_array(($input_string[$i])[-1], ['.',',','!','?'])) {
				$input_string[$i] = substr("$input_string[$i])",0,-2);
			}
		}
		//Убираем повторяющиеся строки из исходного массива и записываем в другой
		return $input_string;
	}

	$date = date("Y/m/d");

	if (!empty($_FILES['doc']['name'])) {
		$countt = word_count(file_get_contents($_FILES['doc']['tmp_name']));
		$insertQuery = 'INSERT INTO `uploaded_text`(content,date_of_insert,words_count) VALUES (?,?,?)';
		$pdo->prepare($insertQuery)->execute([file_get_contents($_FILES['doc']['tmp_name']),$date,$countt]);
		$ID = $pdo->lastInsertId();

		$word_qauntity=[];
		$unique_string = array_unique(text_handling(file_get_contents($_FILES['doc']['tmp_name'])));
		foreach ($unique_string as $value) {
			$qun = 0;
			foreach (text_handling(file_get_contents($_FILES['doc']['tmp_name'])) as $val) {//Подсчет повторений элементов
				if($value===$val){
					$qun++;
				}
			}
			array_push($word_qauntity, $qun);
		}

		$i=0;
		foreach ($unique_string as $value) {
			$insertQuery = 'INSERT INTO `word`(text_id,word,count) VALUES (?,?,?)';
			$pdo->prepare($insertQuery)->execute([$ID,$value,$word_qauntity[$i]]);
			$i+=1;
		}
		header ('Location: index.php');
		exit();
	}

	if (!empty($_POST['usual_text'])) {
		$countt = word_count($_POST['usual_text']);
		$insertQuery = 'INSERT INTO `uploaded_text`(content,date_of_insert,words_count) VALUES (?,?,?)';
		$pdo->prepare($insertQuery)->execute([$_POST['usual_text'],$date,$countt]);
		$ID = $pdo->lastInsertId();


		$word_qauntity=[];
		$unique_string = array_unique(text_handling($_POST['usual_text']));
		foreach ($unique_string as $value) {
			$qun = 0;
			foreach (text_handling($_POST['usual_text']) as $val) {//Подсчет повторений элементов
				if($value===$val){
					$qun++;
				}
			}
			array_push($word_qauntity, $qun);
		}

		$i=0;
		foreach ($unique_string as $value) {
			$insertQuery = 'INSERT INTO `word`(text_id,word,count) VALUES (?,?,?)';
			$pdo->prepare($insertQuery)->execute([$ID,$value,$word_qauntity[$i]]);
			$i+=1;
		}
		header ('Location: index.php');
		exit();
	}

?>
<form method="post" enctype="multipart/form-data">
 	<input type="file" name="doc"></br>
	<textarea name="usual_text"></textarea></br>
	<input type="submit">
</form>