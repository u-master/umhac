<?php

/* Переменные */
	$result = "";

/* Готовимся к запросам */
	$odb = new mysqli ("localhost:3405", "root", "zyadmin", "academy");
	if (mysqli_connect_errno()) die("Error: db01 >> mysqli >> Failed database connection.");
	$odb->set_charset("utf8");

	if(isset($_GET['type'])) {
		$codetype = $_GET['type'];
	}
	else {
		$codetype = "NONE";
	}
	if(isset($_GET['num'])) {
		$codenum = $_GET['num'];
	}
	else {
		$codenum = 0;
	}
	if(isset($_GET['solve'])) {
		$issolve = $_GET['solve'];
	}
	else {
		$issolve = 0;
	}
	$s_query_task="SELECT task.sCode FROM ha_tasks AS task, ha_theory AS theory WHERE theory.id=task.id_theory AND theory.seq_number=".$codenum." AND task.codetype='".$codetype."' AND task.isSolution=".$issolve;

/* Запросы и обработка */
	$query_task = $odb->query($s_query_task);
	if ($query_task) {
		$row=$query_task->fetch_assoc();
		if(isset($row['sCode'])) { $result=$row['sCode']; }
	}

	echo($result);
?>