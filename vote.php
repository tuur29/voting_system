<?php
	ob_start();
	include_once("options.php");
	include_once("assets/language.php");
	
	if (is_dir($folder) && file_exists($folder."/round.txt")){
		if (isset($_POST['question'])) $q = $_POST['question']; else $q = 1;
		$id = file_get_contents($folder."/round.txt",null,null,null,10);
		$nChoices = explode("-", file_get_contents($folder."/round.txt",null,null,11) );
		$allDone = true;
		
		if (isset($_COOKIE[$id])) {
			$votes = explode("-",$_COOKIE[$id]);
			if (count($votes) < count($nChoices) && count($votes)!=$q ) {
				$allDone = false;
			}else if (!isset($_POST['question']) && count($votes) < count($nChoices)){
				$allDone = false;
			}
		}else {
			$votes = array();
			$allDone = false;
		}
		
		if ( !$allDone ) {
			if (isset($_POST['question']) && isset($_POST['choice'])){
				file_put_contents($folder."/".$q.".txt", "-".$_POST['choice'], FILE_APPEND | LOCK_EX);
				array_push($votes,$_POST['choice']);
				setcookie($id,implode("-",$votes),time()+259200);
			}else {
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
		h1 .label { margin: 100px 5px; }
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
			bottom: 15px;
			display: none;
			left: 0;
			text-align: center;
			font-size: 18px;
		}
		#footer a { margin: 0 10px }
		#credits {
			position: absolute;
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
<h1><?php echo $QUESTION." ".$q ?></h1>
<div class="container"></div>

	<!-- Scripts -->
	<script src="assets/jquery-1.10.2.min.js"></script>
	<script>
		var clicked = false;
		var id = <?php echo $id ?>;
		var nChoices = <?php echo json_encode($nChoices) ?>;
		var questions = nChoices.length;
		var question = <?php echo count($votes)+1 ?>;
		var votes = <?php echo json_encode($votes) ?>;
		
		for (var i=0; i<questions; i++){
			var col = Math.floor(12/ nChoices[i] );
			$(".container").append( '<div class="row" id="q'+(i+1)+'">');
			
			for (var n=0; n<nChoices[i]; n++){
				$(".container #q"+(i+1)).append( '<div class="col-md-'+col+'"><div id="b'+(n+1)+'" class="button"><button value="'+(n+1)+'">'+(n+1)+'</button></div></div>' );
			}
			if (col*nChoices[i] != 12){
				$(".container #q"+(i+1)).prepend('<div class="col-md-1"></div>');
				$(".container #q"+(i+1)).append('<div class="col-md-1"></div>');
			}
		}
		
		$(".row").each(function(){
			$(this).css("display","none");
		});
		$("#q"+question).css("display","block");
		$("h1").html("<?php echo $QUESTION ?> "+ question );
		
		$(".row .button").click(function() {
			if (!clicked) {
				clicked = true;
				var choice = $(this).children("button").val();
				question = $(this).children("button").parents(".row").attr('id').substring(1);
				$.ajax({
					type: "POST",
					url: "vote.php",
					data: "question="+question+"&choice="+choice,
					success: function(){
						votes.push(choice);
						nextQuestion(question);
					}
				});
			}
		});
		
		function nextQuestion(q) {
			$("#q"+q).fadeOut(function() {
				if (q<questions){
					$("h1").html("<?php echo $QUESTION ?> "+ (Number(q)+1) );
					$("#q"+ (Number(q)+1) ).fadeIn();
					clicked = false;
				}else {
					var vars = [];
					
					$("h1").html("<?php echo $VOTED ?>:<br/>");
					for (var i=0; i<votes.length; i++) {
						$("h1").append("<span class='label label-"+defineVars(votes[i])[0]+"'>"+defineVars(votes[i])[1]+"</span>");
					}
					
					setTimeout(function(){
						$("h1").animate({
							'margin-top': '200px'
						},1000,function() {
							$("#footer").slideDown();
						});
					},1);
				}
			});
		}
		
	</script>

<?php
			}
		}else {
?>
	<script src="assets/jquery-1.10.2.min.js"></script>
	<script>
		
		var votes = <?php echo json_encode($votes) ?>;
		$("h1").html("<?php echo $VOTED ?>:<br/>");
		for (var i=0; i<votes.length; i++) {
			$("h1").append("<span class='label label-"+defineVars(votes[i])[0]+"'>"+defineVars(votes[i])[1]+"</span> ");
		}
		
		$("h1").css({
			'margin-top': '200px'
		});
		setTimeout(function(){
			$("#footer").css("display","block");
		},50);
		
	</script>
<?php
		}
?>
<div id="footer"><?php 
					if ($seo){
						echo "<a class='btn btn-default' href='".strtolower($RESULTS)."'>".$SEERESULTS."</a>";
					}else {
						echo "<a class='btn btn-default' href='results.php'>".$SEERESULTS."</a>";
					}
?></div>
<div id="credits"><?php echo $CREDITS ?> <a href="http://www.tuurlievens.net/" target="_blank">Tuur Lievens</a>.</div>
</body>
</html>

<?php
	}else {
		echo "<html><body><p>".$NOTACTIVE."</p></body></html>";
	}
	ob_flush();
?>