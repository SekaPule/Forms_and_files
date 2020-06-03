 <form method="post" enctype="multipart/form-data">
 	<input type="file" name="doc"></br>
	<textarea name="usual_text"></textarea></br>
	<input type="submit">
</form>

<?php
	function file_count($way){//Подсчет кол-ва файлов в папке
		$dh = opendir($way);
		$count=0;
		while (($file = readdir($dh))) {
			if ($file=='.' || $file=='..') {
				continue;
			}else{
				$count++;
			}
		}
		closedir($dh);
		return $count;
	}

	function this_is_something_cool($input_string){

		//Разбиваем текст по словам на массив строк
		$array_of_string = explode(" ", $input_string);

		$counter = 0;

		echo "$input_string</br>";

		//Перевод слов текста в нижний регистр и подсчет общего кол-ва слов
		//удаление лишних знаков пунктуации
		for ($i=0; $i < count($array_of_string) ; $i++) {
			$array_of_string[$i] = mb_strtolower("$array_of_string[$i]");
			if (($array_of_string[$i])[-1]==="."||($array_of_string[$i])[-1]===",") {
				$array_of_string[$i] = substr("$array_of_string[$i])",0,-2);
			}
			$counter++;
		}

		$unique_string = array_unique($array_of_string);//Убираем повторяющиеся строки из исходного массива и записываем в другой

		if(!file_exists("csv_results")) {
    		mkdir("csv_results");
		}

		$num = file_count("csv_results/")+1;//костыли
		$dir = opendir("csv_results");
		$file = fopen("csv_results/"."test"."$num".".csv", 'w');//костыли
		foreach ($unique_string as $value) {
			$qun = 0;
			foreach ($array_of_string as $val) {//Подсчет повторений элементов
				if($value===$val){
					$qun++;
				}
			}
			fputcsv($file, explode(",", "{$value},$qun"),";");
		}
		fputcsv($file, explode(",", "Count of words:,$counter"),";");
		fclose($file);
		closedir($dir);


	}

	if (!empty($_FILES['doc']['name'])) {
		this_is_something_cool(file_get_contents($_FILES['doc']['tmp_name']));
	}
	if (!empty($_POST['usual_text'])) {
		this_is_something_cool($_POST['usual_text']);
	}

?>