<?php
	include_once("options.php");
	include_once("assets/language.php");
	if (file_exists("round.txt")){
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $MAKECHOICE ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
	<style>
		body { background-color: #fefefe; }
		h1 {
			text-align: center;
			margin-top: 50px;
		}
		.container { margin-top: 70px; }
		.button {
			display: block;
			background-position: center center;
			background-size: 100% 100%;
			background-repeat: no-repeat;
			background-image: url('assets/white.png');
			margin: 0 auto;
			width: 128px;
			height: 128px;
			position: relative;
			cursor: pointer;
		}
		.button#b1{ background-image: url('assets/buttons/red.png'); }
		.button#b2{ background-image: url('assets/buttons/blue.png'); }
		.button#b3{ background-image: url('assets/buttons/yellow.png'); }
		.button#b4{ background-image: url('assets/buttons/green.png'); }
		.button#b5{ background-image: url('assets/buttons/black.png'); }
		.button#b6{ background-image: url('assets/buttons/pink.png'); }
		.button#b7{ background-image: url('assets/buttons/orange.png'); }
		.button#b8{ background-image: url('assets/buttons/cyan.png'); }
		.button#b9{ background-image: url('assets/buttons/white.png'); }
		button { display: none; }
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
			bottom: 10px;
			left: 0;
			text-align: center;
			display: none;
		}
		#credits {
			position: absolute;
			right: 10px;
			bottom: 10px;
			font-size: 10px;
		}
		@media (max-width: 970px){
			.container { margin-top: 0px; }
			.row div:not(.col-md-1) {
				margin-top: 30px;
			}
			h1 {
				margin: 20px 0 0px 0;
			}
			.button {
				width: 64px;
				height: 64px;
				margin-top: 20px;
			}
		}
	</style>
	
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<h1><?php echo $MAKECHOICE ?></h1>

<?php
	$file = 'round.txt';
	$id = file_get_contents($file,null,null,null,10);
	
	if ( $_COOKIE['id']!=$id ) {
		if (isset($_GET['choice'])){
			$choice = $_GET['choice'];
			file_put_contents($file, "-".$choice, FILE_APPEND | LOCK_EX);
		}else {
?>

<div class="container"></div>

	<!-- Scripts -->
	<script src="assets/jquery-1.10.2.min.js"></script>
	<script src="assets/jquery.cookie.js"></script>
	<script>
		var clicked = false;
		var id;
		var nChoices = 5;
		var choice;
		var color;
		$.ajax({
			url: "round.txt",
			success:function(result){
				id = result.substr(0,10);
				result = result.substr(11);
				nChoices = result.charAt(0);
				result = result.substr(2);
				var col = Math.floor(12/ nChoices );
				for (var i=0; i<nChoices; i++){
					$(".container").append( '<div class="col-md-'+col+'"><div id="b'+(i+1)+'" class="button"><button value="'+(i+1)+'">'+(i+1)+'</button></div></div>' );
				}

				if (col*nChoices != 12){
					$(".container").append('<div class="col-md-1"></div>');
					$(".container").prepend('<div class="col-md-1"></div>');
				}
				
				$(".button").click(function() {
					if (!clicked) {
						clicked = true;
						choice = $(this).children("button").val();
						$.ajax({
							url: "vote.php",
							data: "choice="+choice,
							success:function(){
								$.cookie('choice', choice, { expires: 1 });
								$.cookie('id', id, { expires: 1 });
								switch(Number(choice)){
									case 1:
										color = "red";
										choice = "<?php echo $RED ?>";
										break;
									case 2:
										color = "blue";
										choice = "<?php echo $BLUE ?>";
										break;
									case 3:
										color = "yellow";
										choice = "<?php echo $YELLOW ?>";
										break;
									case 4:
										color = "green";
										choice = "<?php echo $GREEN ?>";
										break;
									case 5:
										color = "black";
										choice = "<?php echo $BLACK ?>";
										break;
									case 6:
										color = "pink";
										choice = "<?php echo $PINK ?>";
										break;
									case 7:
										color = "orange";
										choice = "<?php echo $ORANGE ?>";;
										break;
									case 8:
										color = "cyan";
										choice = "<?php echo $CYAN ?>";
										break;
									default:
										color = "white";
										choice = "<?php echo $WHITE ?>";
										break;
								}
								hide("<?php echo $VOTED1 ?> <span class='label label-"+color+"'>"+choice+"</span><?php echo $VOTED2; ?>");
							},
							error:function(){
								$("#footer").html("<a href=''><?php echo $AGAIN ?></a>");
								hide("<?php echo $TRYAGAIN ?><br/>");
							},
						});
					}
				});
			
			},
			error:function(error){
				$("html").append("<p><?php echo $TRYAGAIN ?></p>");
			},
		 });
		
		function hide(text) {
			$(".button").fadeOut(function() {
				$("h1").html(text);
				setTimeout(function(){
					$("h1").animate({
							'margin-top': '200px'
					},2000,function() {
						$("#footer").slideDown();
					});
				},1);
			});
		}
		
	</script>

<?php
		}
	}else {
		$choice = $_COOKIE['choice'];
		switch ($choice) {
			case 1:
				$color = "red";
				$choice = $RED;
				break;
			case 2:
				$color = "blue";
				$choice = $BLUE;
				break;
			case 3:
				$color = "yellow";
				$choice = $YELLOW;
				break;
			case 4:
				$color = "green";
				$choice = $GREEN;
				break;
			case 5:
				$color = "black";
				$choice = $BLACK;
				break;
			case 6:
				$color = "pink";
				$choice = $PINK;
				break;
			case 7:
				$color = "orange";
				$choice = $ORANGE;
				break;
			case 8:
				$color = "cyan";
				$choice = $CYAN;
				break;
			default:
				$color = "white";
				$choice = $WHITE;
				break;
		}

?>
	<script src="assets/jquery-1.10.2.min.js"></script>
	<script>

		$("h1").html("<?php echo $VOTED1 ?> <span class='label label-<?php echo $color ?>'><?php echo $choice ?></span> <?php echo $VOTED2; ?>");
		$("h1").css({
			'margin-top': '200px'
		});
		setTimeout(function(){
			$("#footer").slideDown();
		},500);
		
	</script>
<?php
	}
?>
<div id="footer"><a href="results.php"><?php echo $SEERESULTS ?></a></div>
<div id="credits"><?php echo $CREDITS ?> <a href="http://www.tuurlievens.net/" target="_blank">Tuur Lievens</a>.</div>
</body>
</html>

<?php
	}else {
?>

<p><?php echo $TRYAGAIN ?></p>

<?php
	}
?>