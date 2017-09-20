<?php
/* Готовимся к запросам */
	$odb = new mysqli ("localhost:3405", "root", "zyadmin", "academy");
	if (mysqli_connect_errno()) die("Error: db01 >> mysqli >> Failed database connection.");
	$odb->set_charset("utf8");
	
	$s_query_allcourses="SELECT id, seq_number, name, description FROM ha_courses ORDER BY seq_number";
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<title>HTML Academy - &lt;Все курсы&gt;</title>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
</head>
<body>

<header class="topheader clearfix">
	<a class="help" href="http://умастер.рф"><img class="logoimg" src="images/logo.png" alt="LOGO"></a>
	<h2> Курсы HTML Academy</h2>
</header>

<!-- Таблица всех курсов -->
<div class="courses">
<?php
	$query_allcourses = $odb->query($s_query_allcourses);
	if ($query_allcourses) {
		echo "<div class='table'>\n";
		while ($row=$query_allcourses->fetch_assoc()) {
			echo "\t<div class='tablerow'>\n\t\t<div class='tabledata courses-item'>".$row['seq_number'].".</div>\n\t\t<div class='tabledata courses-item'><a href='/myacademy/course.php?id=".$row['id']."'>".$row['name']."</a></div>\n\t\t<div class='tabledata courses-item'>".$row['description']."</div>\n\t</div>\n";
		}
		echo "</div>\n";
		$query_allcourses->close();
	}
?>
</div>

<!-- Футер -->

<footer class="bottomfooter clearfix">
	<a href="http://умастер.рф"><img class="logoimg logowhite" src="images/logo.png" alt="LOGO"></a>
	<a href="https://htmlacademy.ru/"><img class="logoimg" src="images/logo-ha.png" alt="HTMLAcademy"></a>
	<p class="copyright">© ООО «Интерактивные обучающие технологии» (HTML Academy), 2013−2017
		<br><br>Незаконная копия, однако автор страницы не делится ею ни с кем и использует информацию сугубо в личных целях! <br> Если Вы не автор страницы - покиньте, пожалуйста, помещение!!! <br>&nbsp;&nbsp;= У-Мастер =
	</p>
</footer>

</body>
</html>

<?php
	$odb->close();
?>