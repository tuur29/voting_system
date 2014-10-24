<?php

ob_start();
session_start();
include_once("defaults.php");

function deldir($dir) {
	foreach(glob($dir . '/*') as $file) {
		if(is_dir($file)) rrmdir($file); else unlink($file);
	}
	rmdir($dir);
}

$id = '0';
if (is_dir($folder) && file_exists($folder."/round.txt")){
	$id = file_get_contents($folder."/round.txt",null,null,null,10);
}

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"]!=$id) {

?>

<form action="assets/login.php" method="post">
	<input name="pass" type="password" placeholder="<?php echo $PASSWORD ?>" autofocus />
	<input type="submit" value="<?php echo $LOGIN ?>" />
</form>

<?php
	return;
}

if (isset($_POST['incr'])){
	$n = intval(file_get_contents($folder."/active.txt"));
	$nChoices = explode($separator, file_get_contents($folder."/round.txt",null,null,11) );
	if ($n>=count($nChoices)) {
		file_put_contents($folder."/active.txt", '-1', LOCK_EX);
	}else {
		file_put_contents($folder."/active.txt", $n+1, LOCK_EX);
		echo $n+1;
	}
	return;
}

if (isset($_POST['remove'])){
	deldir($_POST['remove']);
	$href="results.php";
	if ($seo) $href = strtolower($RESULTS);
	echo "<script>window.location = './".$href."';</script>";
	return;
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $RESET; ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries-->
	<!--[if lt IE 9]>
	<script src="assets/html5shiv.js"></script>
	<script src="assets/respond.min.js"></script>
	<![endif]-->
	<style>
		
		.alert, .row { margin-top: 50px; }
		.col-md-8 {
			background: #f6f6f6;
			border-radius: 10px;
			padding: 25px;
		}
		#choices input[type="number"].form-control{
			display: inline-block;
			width: 70px;
			margin: 4px 4px 0 0;
		}
		#keepBox {
			display: none;
			width: auto;
		}
		
		.checkbox {
			display: inline-block;
			padding: 0 5px 7px 30px;
		}
		
		@media (max-width: 768px){
			body { background: #efefef; }
			.alert, .row { margin-top: 30px; }
			.col-md-8 {
				padding: 0 25px;
				background: none;
				border-radius: 0px;
			}
			#keepBox { width: 100%; }
		}
		
	</style>
</head>
<body>
<div class="container">
		
<?php

if (isset($_POST['questions']) && $_POST['questions']) {

	for ($i=0;$i<$_POST['questions'];$i++){
		$choices[$i] = $_POST['choices'.($i+1)];
	}

	if (is_dir($folder)){
		if (isset($_POST['keep'])){
			$keep = $_POST['keep'];
			while (is_dir($keep)) {
				$keep = $keep.'-';
			}
			rename($folder,$keep);
		}else {
			deldir($folder);
		}
	}
	
	$id = time();
	mkdir($folder);
	file_put_contents($folder."/round.txt", $id, LOCK_EX);

	if (isset($_POST['step'])) {
		file_put_contents($folder."/active.txt", "0", LOCK_EX);
	}else {
		file_put_contents($folder."/active.txt", count($choices)+1, LOCK_EX);
	}

	for ($i=0; $i<count($choices); $i++){
		if ($choices[$i]=="") $choices[$i] = 1;
		file_put_contents($folder."/round.txt", $separator.$choices[$i], FILE_APPEND | LOCK_EX);
		file_put_contents($folder."/".($i+1).".txt", "0", LOCK_EX);
	}
	
	$_SESSION['loggedin'] = $id;
	
	if (!isset($_GET["iframe"])) {
		$href="results.php";
		if ($seo) $href = strtolower($RESULTS);
		echo "<script>window.location = './".$href."';</script>";
		return;
	}
	
	echo '<div class="alert alert-success">'. $SYSTEMRESET .'</div>';
	echo "<script>setTimeout(function() { parent.location.reload(); },750);</script>";
}

?>
	
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8">
			<form class="form-horizontal" action="" method="POST">
				<div class="form-group">
					<label for="reset" class="col-sm-4 control-label"><?php echo $NUMBERQUESTIONS ?></label>
					<div class="col-sm-8">
						<input type="number" id="questions" name="questions" min="1" value="1" class="form-control" placeholder="0">
					</div>
				</div>
				<div class="form-group">
					<label for="reset" class="col-sm-4 control-label"><?php echo $NUMBERCHOICES ?></label>
					<div class="col-sm-8" id="choices">
						<input type="number" name="choices1" min="1" class="form-control" placeholder="Q1">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<div class="checkbox">
							<label>
								<input id="step" name="step" type="checkbox"><?php echo $STEPBYSTEP ?>
							</label>
						</div>
						<?php if (is_dir($folder) && file_exists($folder."/round.txt")){ ?>
						<div class="checkbox">
							<label>
								<input id="keep" type="checkbox"><?php echo $KEEPFOLDER ?>
							</label>
						</div>
						<input type="text" id="keepBox" class="form-control" placeholder="<?php echo $OLDROUNDNAME; ?>">
						<?php } ?>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-primary">
							<?php 
								if (is_dir($folder) && file_exists($folder."/round.txt")){
									echo $RESET;
								} else {
									echo $START;
								}
							?>
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-2"></div>
	</div>
	
</div>

<script src="assets/jquery-1.10.2.min.js"></script>
<script>
	
	var oldq = 1;
	$("#questions").change(function(){
		var q = Number($(this).val());
		
		if (q<oldq){
			for (var i=0;i<(oldq-q);i++){
				$("#choices input:last").remove();
			}
		}else if (q>oldq){
			for (var i=q;i>oldq;i--){
				var v = q-i+oldq+1;
				$("#choices").append('<input type="number" name="choices'+v+'" min="1" class="form-control" placeholder="Q'+v+'">');
			}
		}
		oldq = q;
	});
	
	$("#questions").keydown(function(e){
		if (e.which == 13){
			e.preventDefault;
			return false;
			$("input").focus();
		}
	});
	
	$('#keep').change(function(){
		if (this.checked){
			$('#keepBox').show();
			$('#keepBox').attr("name","keep");
		}else {
			$('#keepBox').removeAttr("name");
			$('#keepBox').hide();
		}
	});
	
	setTimeout(function(){
		$(".alert").slideUp(function(){$(this).remove()});
	},1000);
	
</script>

</body>
</html>

<?php ob_flush(); ?>