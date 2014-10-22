<?php

ob_start();
include_once("defaults.php");

if (is_dir($folder) && file_exists($folder."/round.txt")){
	
	$chartest = [ 0, 'abc', [0,'abc'] ];
	if (!isset($_COOKIE["chartest"])){
		setcookie("chartest", json_encode($chartest),time()+259200);
		header("refresh: 0;");
		return;
	}
	if (json_decode($_COOKIE["chartest"]) != $chartest ) {
		echo $NOTCOMPATIBLE;
		return;
	}
	
	$id = file_get_contents($folder."/round.txt",null,null,null,10);
	$votes = [];
	$nChoices = explode($separator, file_get_contents($folder."/round.txt",null,null,11) );
	$activeQuestion = file_get_contents($folder."/active.txt");
	if (isset($_COOKIE[$id])) $votes = json_decode($_COOKIE[$id]);
	
	if (isset($_POST['question']) && isset($_POST['choice']) && $_POST['question'] <= count($nChoices) && $_POST['question'] <= $activeQuestion && $_POST['choice'] <= $nChoices[$_POST['question']-1]){
		$notvoted = true;
		foreach ($votes as $v) {
			if ($v[0]==$_POST['question']) {
				$notvoted = false;
				break;
			}
		}
		if ($notvoted) {
			array_push($votes,[$_POST['question'],$_POST['choice']]);
			file_put_contents($folder."/".$_POST['question'].".txt", $separator.$_POST['choice'], FILE_APPEND | LOCK_EX);
			setcookie($id,json_encode($votes),time()+259200);
		}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?php echo $VOTING ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
		<link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries-->
		<!--[if lt IE 9]>
<script src="assets/html5shiv.js"></script>
<script src="assets/respond.min.js"></script>
<![endif]-->
		<style>
			body { background-color: #fefefe; }
			h1 {
				text-align: center;
				margin-top: 50px;
			}
			h2 {
				text-align: center;
				margin-top: 50px;
			}
			.container { margin-top: 60px; }
			.button {
				display: block;
				background-position: center center;
				background-size: 100% 100%;
				background-repeat: no-repeat;
				background-image: url('assets/buttons/white.png');
				margin: 0 auto;
				width: 128px;
				height: 128px;
				position: relative;
				cursor: pointer;
			}
			button { display: none; }
			.button#b1{ background-image: url('assets/buttons/red.png'); }
			.button#b2{ background-image: url('assets/buttons/blue.png'); }
			.button#b3{ background-image: url('assets/buttons/yellow.png'); }
			.button#b4{ background-image: url('assets/buttons/green.png'); }
			.button#b5{ background-image: url('assets/buttons/black.png'); }
			.button#b6{ background-image: url('assets/buttons/pink.png'); }
			.button#b7{ background-image: url('assets/buttons/orange.png'); }
			.button#b8{ background-image: url('assets/buttons/cyan.png'); }
			.button#b9{ background-image: url('assets/buttons/white.png'); }
			h1 .label {
				line-height: 2.5;
				margin: 0 5px;
			}
			.label-red { background-color: #FF0A0A; }
			.label-blue { background-color: #235BF3; }
			.label-yellow { background-color: #FFDB0A; }
			.label-green { background-color: #1FB01F; }
			.label-black { background-color: #222222; }
			.label-pink { background-color: #F70A9F; }
			.label-orange { background-color: #FF9116; }
			.label-cyan { background-color: #0ED5F1; }
			.label-white { background-color: #aaaaaa; }
			#footer {
				position: absolute;
				right: 0;
				bottom: 11px;
				display: none;
				left: 0;
				text-align: center;
				font-size: 18px;
			}
			#footer a { margin: 0 10px }
			#credits {
				position: fixed;
				right: 10px;
				bottom: 10px;
				font-size: 10px;
			}
			@media (max-width: 970px){
				.container { margin-top: 0px; }
				h1 { margin: 20px 0 0px 0; }
				.button {
					width: 64px;
					height: 64px;
					margin-top: 10px;
				}
			}
		</style>
		<script src="assets/jquery-1.10.2.min.js"></script>
		<script>
			function defineVars(n){
				switch(Number(n)){
					case 1:
						return ["red","<?php echo $RED ?>"];
						break;
					case 2:
						return ["blue","<?php echo $BLUE ?>"];
						break;
					case 3:
						return ["yellow","<?php echo $YELLOW ?>"];
						break;
					case 4:
						return ["green","<?php echo $GREEN ?>"];
						break;
					case 5:
						return ["black","<?php echo $BLACK ?>"];
						break;
					case 6:
						return ["pink","<?php echo $PINK ?>"];
						break;
					case 7:
						return ["orange","<?php echo $ORANGE ?>"];
						break;
					case 8:
						return ["cyan","<?php echo $CYAN ?>"];
						break;
					default:
						return ["white","<?php echo $WHITE ?>"];
						break;
				}
			}
		</script>
		<meta name="robots" content="noindex,nofollow" />
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body>
<?php if ($activeQuestion==0) { ?>
<h1 style='margin-top: 200px;'><?php echo $WAITSTART ?></h1>
		<?php if ($reloadactive) { ?>
<script>
	var active = <?php echo $activeQuestion ?>;
	var loop = setInterval(function(){
		$.ajax({
			url: "<?php echo $folder ?>/active.txt",
			success: function(data){
				if (data>parseInt(active)) {
					location.reload(true);
				}
			}
		});
	},<?php echo $refreshinterval; ?>);
</script>
		
	<?php
		}
		return;
	} else if ($activeQuestion<0) {
	?>
	<h1 style='margin-top: 200px;'><?php echo $NOTACTIVE ?></h1>
	<?php
		return;
	}
	
	if (!isset($_COOKIE[$id])) {
		if (isset($notvoted) && $notvoted) {
			$lowest = $activeQuestion;
		}else {
			$lowest=1;
		}
	} else {
		$lowest = count($nChoices)+1;
	}
	
	foreach ($votes as $v) {
		if ($v[0] <= $lowest) $lowest=$v[0]+1;
	}
	
	if ( $lowest <= count($nChoices) && $lowest <= $activeQuestion ) {
		$q = $lowest;
		if ( $activeQuestion <= count($nChoices) ) $q = $activeQuestion;
?>

<h1><?php echo $QUESTION." ".$q ?></h1>
<div class="container">
	<div class="row">
<?php
	$col = floor(12/ $nChoices[$q-1]);
	if ($col*$nChoices[$q-1] != 12)	echo '<div class="col-md-1"></div>';
	for ($i=1;$i<$nChoices[$q-1]+1;$i++) {
		echo '<form action="" method="POST" class="col-md-'.$col.'"><div id="b'.$i.'" class="button"></div><input type="hidden" name="question" value="'.$q.'" /><input type="hidden" name="choice" value="'.$i.'" /></form>';
	}
	if ($col*$nChoices[$q-1] != 12)	echo '<div class="col-md-1"></div>';
?>
	</div>
</div>
	<script>
		$('.container').hide();
		$('.container').fadeIn(200);
		$(".row .button").click(function() {
			t = $(this);
			$('.container').fadeOut(function(){
				t.parent().submit();
			});
		});
		
	</script>

<?php
	}else {
		if (!$hideownvotes) {
			echo '<h1>'.$VOTEDFOR.':<br/></h1>';
		} else {
			echo '<h1>'.$VOTED.'</h1>';
		}
		
		if (!$onevote) {
			setcookie($id, "", time()-1);
			echo "<h2><a href=''>".$VOTEAGAIN."</a></h2>";
		}
?>
	<script>
		$("h1").hide();
		$("h1").fadeIn();
		$('h1').animate({'margin-top': '200px'});
		
		setTimeout(function(){
			$("#footer").slideDown(400);
		},600);
		
		<?php if (!$hideownvotes) { ?>
		
		var votes = <?php echo json_encode($votes) ?>;
		for (var i=0; i<votes.length; i++) {
			$("h1").append("<span class='label label-"+defineVars(votes[i][1])[0]+"'>"+votes[i][0]+': '+defineVars(votes[i][1])[1]+"</span>");
		}
		
		<?php
		}
		if ($reloadactive) {
		?>
		
		var active = <?php echo $activeQuestion ?>;
		var loop = setInterval(function(){
			$.ajax({
				url: "<?php echo $folder ?>/active.txt",
				success: function(data){
					if (data>parseInt(active)) {
						location.reload(true);
					}
				}
			});
		},<?php echo $refreshinterval; ?>);
		
<?php
		}
	echo '</script>';
	}
		
	if ($resultslink) {
		echo '<div id="footer">';
		$href = "results.php";
		if ($seo) $href = strtolower($RESULTS);
		echo "<a class='btn btn-default' target='_blank' href='".$href."'>".$SEERESULTS."</a></div>";
	}
?>

<div id="credits"><?php echo $CREDITS ?> <a href="http://www.tuurlievens.net/" target="_blank">Tuur Lievens</a>.</div>
</body>
</html>

<?php
	
}else {
	echo "<html><body><p>".$NOTACTIVE."</p></body></html>";
}
ob_flush();

?>