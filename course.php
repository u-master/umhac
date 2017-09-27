<?php
/* Переменные */
	$coursename = "UNKNOWN"; //Строка с названием выбранного курса
	$query_coursetheory = ""; //Результат запроса по теории
	$maxnumber=0; //Максимальный номер параграфа. То есть не факт, что в базе нумерация материала будет строго соблюдена
	$navigationstr=""; //Коли проходим по запросу с параграфами - сгенерим строку меню всех параграфов
	$id = 1;  // ID текущего курса
	$prevcourseid = $nextcourseid = 0;  // ID предыдущего и следующего курса

/* Готовимся к запросам */
	$odb = new mysqli ("localhost:3405", "root", "zyadmin", "academy");
	if (mysqli_connect_errno()) die("Error: db01 >> mysqli >> Failed database connection.");
	$odb->set_charset("utf8");

	if(isset($_GET['id'])) {
		$id = $_GET['id'];
	}
	$s_query_coursetitle="SELECT seq_number, name FROM ha_courses WHERE id=".$id;
	$s_query_coursetheory="SELECT id, seq_number, title, theory_html FROM ha_theory WHERE id_course=".$id." ORDER BY seq_number ASC";
	$s_query_allcourses="SELECT id, seq_number FROM ha_courses ORDER BY seq_number";

/* Запросы и обработка */
	$query_coursetitle = $odb->query($s_query_coursetitle);
	if ($query_coursetitle) {
		$row=$query_coursetitle->fetch_assoc();
		if(!isset($row['seq_number'])) { $row['seq_number'] = 1; }
		if(!isset($row['name'])) { $row['name'] = "UNKNOWN"; }
		$coursename=$row['seq_number']." - ".$row['name'];
	}

	$query_coursetheory = $odb->query($s_query_coursetheory);
	if ($query_coursetheory) {
		while ($row=$query_coursetheory->fetch_assoc()) {
			$maxnumber=($row['seq_number']>$maxnumber) ? $row['seq_number'] : $maxnumber;
			$navigationstr.=" <li class='tablerow tocitem__collapsable'><a class='tabledata' href='#theory".$row['seq_number']."'>".$row['seq_number'].". ".$row['title'].".</a></li>\n";
		}
		$query_coursetheory->data_seek(0);
	}

	$query_allcourses = $odb->query($s_query_allcourses);
	if ($query_allcourses) {
		while ($row=$query_allcourses->fetch_assoc()) {
			if ($row['id']==$id) {
				if ($row=$query_allcourses->fetch_assoc()) {
					$nextcourseid=$row['id'];
				}
				break;
			}
			$prevcourseid = $row['id'];
		}
	}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<?php  
		echo "<title>HTML Academy - &lt;".$coursename."&gt;</title>\n";
	?>
	<link rel="stylesheet" type="text/css" href="styles/style.css">
	<script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
</head>
<body>
<header class="topheader clearfix">
	<a class="help" href="http://умастер.рф"><img class="logoimg" src="images/logo.png" alt="LOGO"></a>
	<?php
		echo "<h2> Курс: ".'"№'.$coursename.'"'.".</h2>\n";
	?>
</header>

<nav class="course_navigation clearfix">
	<ul>
		<li class="a-btn"><a <?php echo (($prevcourseid)?'href="/myacademy/course.php?id='.$prevcourseid.'"':'class="course_navigation__inactive"'); ?> >Предыдущий курс</a></li>
		<li class="a-btn"><a href="/myacademy/allcourses.php">Список курсов</a></li>
		<li class="a-btn"><a <?php echo (($nextcourseid)?'href="/myacademy/course.php?id='.$nextcourseid.'"':'class="course_navigation__inactive"'); ?> >Следующий курс</a></li>
	</ul>
</nav>

<nav class="toc_theory">
	<ul class="table">
		<li class="collapse_toc"><a class="clearfix" href="javascript:void(0)"><div class="collapse_toc_arrow"></div></a></li>
		<?php echo $navigationstr; ?>
	</ul>
</nav>

<?php
	if ($query_coursetheory) {
		echo "<section class='theory'>";
		while ($row=$query_coursetheory->fetch_assoc()) {
			echo "\t<section id='theory".$row['seq_number']."'>\n";
			echo "\t\t<header>\n";
			echo "\t\t\t<h3>№".$row['seq_number']."/".$maxnumber.". ".$row['title'].".</h3><a class='a-btn collapse_task' data-seq-number='".$row['seq_number']."' href='javascript:void(0)'></a>\n";
			echo "\t\t</header>\n";
			echo "\t\t<article>\n";
			echo "\t\t\t".$row['theory_html']."\n";
			echo "\t\t</article>\n";
			echo "\t\t<article class='theory_task clearfix'>\n";
			echo "\t\t\t<code id=task".$row['seq_number']."></code>\n";
			echo "\t\t\t<div class='task_code'>\n";
			echo "\t\t\t\t<div class='task_code_controls clearfix'>\n";
			echo "\t\t\t\t\t<div class='task_code_label_html'>HTML</div>\n";
			echo "\t\t\t\t\t<a class='a-btn show-solution' href='javascript:void(0)' data-target-editor='HTML' data-seq-number='".$row['seq_number']."'>Решение</a>\n";
			echo "\t\t\t\t</div>\n";
			echo "\t\t\t\t<div id='editor_HTML".$row['seq_number']."'></div>\n";
			echo "\t\t\t</div>\n";
			echo "\t\t\t<div class='task_code'>\n";
			echo "\t\t\t\t<div class='task_code_controls clearfix'>\n";
			echo "\t\t\t\t\t<div class='task_code_label_css'>CSS</div>\n";
			echo "\t\t\t\t\t<a class='a-btn show-solution' href='javascript:void(0)' data-target-editor='CSS' data-seq-number='".$row['seq_number']."'>Решение</a>\n";
			echo "\t\t\t\t</div>\n";
			echo "\t\t\t\t<div id='editor_CSS".$row['seq_number']."'></div>\n";
			echo "\t\t\t</div>\n";
			echo "\t\t\t<div class='task_code'>\n";
			echo "\t\t\t\t<div class='task_code_controls clearfix'>\n";
			echo "\t\t\t\t\t<div class='task_code_label_js'>JS</div>\n";
			echo "\t\t\t\t\t<a class='a-btn show-solution' href='javascript:void(0)' data-target-editor='JS' data-seq-number='".$row['seq_number']."'>Решение</a>\n";
			echo "\t\t\t\t</div>\n";
			echo "\t\t\t\t<div id='editor_JS".$row['seq_number']."'></div>\n";
			echo "\t\t\t</div>\n";
			echo "\t\t</article>\n";
			echo "\t</section>\n";
		}
		echo "</section>";
	}
?>

<footer class="bottomfooter clearfix">
	<a href="http://умастер.рф"><img class="logoimg logowhite" src="images/logo.png" alt="LOGO"></a>
	<a href="https://htmlacademy.ru/"><img class="logoimg" src="images/logo-ha.png" alt="HTMLAcademy"></a>
	<p class="copyright">© ООО «Интерактивные обучающие технологии» (HTML Academy), 2013−2017
		<br><br>Незаконная копия, однако автор страницы не делится ею ни с кем и использует информацию сугубо в личных целях! <br> Если Вы не автор страницы - покиньте, пожалуйста, помещение!!! <br>&nbsp;&nbsp;Спасибо!
	</p>
</footer>


<script type="text/javascript" src="js/ace-min-noconflict/ace.js" charset="utf-8"></script>
<script type="text/javascript">
	$(function() {
		/*Define variables*/
		// Object of ACE Editors in collapsed tasks.
		var objTask = function(nNumber) {
			this._n = nNumber;
			this._initEdits ();
		}
		objTask.prototype = {
			_initEdits : function() {
				this._h = ace.edit("editor_HTML"+this._n);
				this._h.setTheme("ace/theme/crimson_editor");
				this._h.getSession().setMode("ace/mode/html");
				this._h.getSession().setUseSoftTabs(true);
				this._h.getSession().setUseWrapMode(true);
				this._h.$blockScrolling = Infinity;
				this._c = ace.edit("editor_CSS"+this._n);
				this._c.setTheme("ace/theme/crimson_editor");
				this._c.getSession().setMode("ace/mode/css");
				this._c.getSession().setUseSoftTabs(true);
				this._c.getSession().setUseWrapMode(true);
				this._c.$blockScrolling = Infinity;
				this._j = ace.edit("editor_JS"+this._n);
				this._j.setTheme("ace/theme/crimson_editor");
				this._j.getSession().setMode("ace/mode/javascript");
				this._j.getSession().setUseSoftTabs(true);
				this._j.getSession().setUseWrapMode(true);
				this._j.$blockScrolling = Infinity;
				this.getTaskAJAX();
			},
			getTaskAJAX : function(typeCode) {
				if (!typeCode || typeCode=="HTML") {
					$.ajax({
						url: "ajax/reqtasksrc.php?type=html&num="+this._n, 
						dataType: "html",
						method: "GET",
						context: this,
						success: function (data) {
							this._h.setValue(data);
							this._h.gotoLine(1);
						}
					});
				}
				if (!typeCode || typeCode=="CSS") {
					$.ajax({
						url: "ajax/reqtasksrc.php?type=css&num="+this._n, 
						dataType: "text",
						method: "GET",
						context: this,
						success: function (data) {
							this._c.setValue(data);
							this._c.gotoLine(1);
						}
					});
				}
				if (!typeCode || typeCode=="JS") {
					$.ajax({
						url: "ajax/reqtasksrc.php?type=js&num="+this._n, 
						dataType: "text",
						method: "GET",
						context: this,
						success: function (data) {
							this._j.setValue(data);
							this._j.gotoLine(1);
						}
					});
				}
			},
			getSolveAJAX : function(typeCode) {
				if (!typeCode || typeCode=="HTML") {
					$.ajax({
						url: "ajax/reqtaskslv.php?type=html&num="+this._n, 
						dataType: "html",
						method: "GET",
						context: this,
						success: function (data) {
							this._h.setValue(data);
							this._h.gotoLine(1);
						}
					});
				}
				if (!typeCode || typeCode=="CSS") {
					$.ajax({
						url: "ajax/reqtaskslv.php?type=css&num="+this._n, 
						dataType: "text",
						method: "GET",
						context: this,
						success: function (data) {
							this._c.setValue(data);
							this._c.gotoLine(1);
						}
					});
				}
				if (!typeCode || typeCode=="JS") {
					$.ajax({
						url: "ajax/reqtaskslv.php?type=js&num="+this._n, 
						dataType: "text",
						method: "GET",
						context: this,
						success: function (data) {
							this._j.setValue(data);
							this._j.gotoLine(1);
						}
					});
				}
			}
		}

		/* Toggle collapse TOC */
		$(".collapse_toc a").click(function(){
			$(".tocitem__collapsable").toggle();
		});

		/* Toggle collapse task */
		$(".theory_task").hide();
		$(".collapse_task").click(function(){
			var nTheoryNumber=$(this).data("seqNumber");
			var parentSection=$(this).parents("#theory"+nTheoryNumber)[0];
			/* Collapse container with task */
			$("#theory"+nTheoryNumber+" .theory_task").toggle();
			/* Toggle button to active state */
			$(this).toggleClass("a-btn__pushed");
			if ($(this).hasClass("a-btn__pushed") && !parentSection.taskElem) {
				parentSection.taskElem=new objTask(nTheoryNumber);
			}
			return true;
		});

		/* Toggle solve task */
		$(".show-solution").click(function(){
			var targetEditor=$(this).data("targetEditor");
			var nTheoryNumber=$(this).data("seqNumber");
			var curObjTask=$(this).parents("#theory"+nTheoryNumber)[0].taskElem;
			if(curObjTask) {
				/* Toggle button to active state */
				$(this).toggleClass("a-btn__pushed");
				if ($(this).hasClass("a-btn__pushed")) {
					curObjTask.getSolveAJAX(targetEditor);
				}
				else {
					curObjTask.getTaskAJAX(targetEditor);
				}
			}
		});
	});
</script>

</body>
</html>



<?php
	$query_coursetitle->close();
	$query_coursetheory->close();
	$query_allcourses->close();
	$odb->close();
?>