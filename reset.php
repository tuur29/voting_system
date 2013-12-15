<?php
	include_once("options.php");
	include_once("assets/language.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $RESET ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<style>
		
		.alert, .row { margin-top: 50px; }
		.col-md-8 {
			background: #efefef;
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
			width: auto
		}
		
<?php if (is_dir($folder) && file_exists($folder."/round.txt")){ ?>
		#disableBtn { display: inline-block; }
		.checkbox {
			display: inline-block;
			padding: 0 5px 7px 30px;
		}
<?php }else { ?>
		#disableBtn { display: none; }
		.checkbox { display: none; }
<?php } ?>
		
		@media (max-width: 768px){
			body { background: #efefef; }
			.alert, .row { margin-top: 30px; }
			.col-md-8 {
				padding: 0 25px;
				background: none;
				border-radius: 0px;
			}
		}
		
	</style>
</head>
<body>
<div class="container">
<?php
		if (isset($_GET['success'])){
?>

	<div class="alert alert-success"><?php echo $SYSTEMRESET ?></div>

<?php
	}else if (isset($_GET['error'])){
?>

	<div class="alert alert-danger"><?php echo $ERROR ?></div>

<?php
	
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
					<label for="password" class="col-sm-4 control-label"><?php echo $PASSWORD ?></label>
					<div class="col-sm-8">
						<input type="password" name="password" class="form-control" placeholder="<?php echo $PASSWORD ?>">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-4 col-sm-8">
						<button type="submit" class="btn btn-default"><?php 
																		if (is_dir($folder) && file_exists($folder."/round.txt")){
																			echo $RESET;
																		} else {
																			echo $START;
																		}
																		?></button>
						<button id="disableBtn" name="disable" type="submit" class="btn btn-default"><?php echo $DISABLE ?></button>
						<div class="checkbox">
							<label>
						  		<input type="checkbox"> <?php echo $KEEPFOLDER ?>
							</label>
					  	</div>
						<input type="text" id="keepBox" name="keep" class="form-control" value="1739204639" placeholder="new round name">
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
	
	$('.checkbox input').change(function(){
		if (this.checked){
			$('#keepBox').val("");
			$('#keepBox').show();
		}else {
			$('#keepBox').val("1739204639");
			$('#keepBox').hide();
		}
	});
	
	setTimeout(function(){
		$(".alert").slideUp();
	},1000);
	
</script>
	
<?php
	if (isset($_POST['questions'])){
		for ($i=0;$i<$_POST['questions'];$i++){
			$choices[$i] = $_POST['choices'.($i+1)];
		}
	}

	if (isset($_POST['disable']) && isset($_POST['password']) && $_POST['password']==$pass){
		if (is_dir($folder)){
			deldir($folder);
		}
	
?>
	
	<script>
		var url = window.location.href;
		console.log(url);
		window.location.replace(url.substring(0, url.indexOf('?'))+"?success");
	</script>
	
<?php
		
	}else if (!isset($_POST['disable']) && isset($_POST['password']) && $_POST['password']==$pass && isset($choices)) {
		if (is_dir($folder)){
			deldir($folder);
		}
		mkdir($folder);
		file_put_contents($folder."/round.txt", time(), LOCK_EX);
		for ($i=0; $i<count($choices)+0; $i++){
			file_put_contents($folder."/round.txt", "-".$choices[$i], FILE_APPEND | LOCK_EX);
			file_put_contents($folder."/".($i+1).".txt", "0", LOCK_EX);
		}
?>
	
	<script>
		var url = window.location.href;
		console.log(url);
		window.location.replace(url.substring(0, url.indexOf('?'))+"?success");
	</script>
	
<?php
	}else if (isset($_POST['password'])) {
?>

	<script>
		var url = window.location.href;
		console.log(url);
		window.location.replace(url.substring(0, url.indexOf('?'))+"?error");
	</script>
	
<?php
	}

	function deldir($dir) {
		if ($_POST['keep']!="1739204639"){
			rename($dir,$_POST['keep']);
		}else {
			foreach(glob($dir . '/*') as $file) {
				if(is_dir($file)) rrmdir($file); else unlink($file);
			}
			rmdir($dir);
		}
	}

?>
	
</body>
</html>