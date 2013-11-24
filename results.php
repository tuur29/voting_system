<?php
	include_once("options.php");
	include_once("assets/language.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $RESULTS ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link rel="shortcut icon" type="image/x-icon" href="assets/favicon.ico" />
	
	<style>
		body { background-color: #fefefe; }
		.container { margin-top: 50px; }
		#count {
			margin: 5px;
			margin-left: 20px;
		}
		td {
			padding: 5px;
			font-size: 16px;
			vertical-align: text-bottom;
		}
		table { margin-left: 20px; }
		.label-red { background-color: #FF0A0A; }
		.label-blue { background-color: #235BF3; }
		.label-yellow { background-color: #FFDB0A; }
		.label-green { background-color: #1FB01F; }
		.label-black { background-color: #222222; }
		.label-pink { background-color: #F70A9F; }
		.label-orange { background-color: #FF9116; }
		.label-cyan { background-color: #0ED5F1; }
		.label-white { background-color: #aaaaaa; }
		.percent { font-weight: bold; }
		#graph { height: 300px; }
		.col-sm-7 { margin-top: 20px; }
		#buttons {
			position: absolute;
			bottom: 20px;
			left: 20px;
		}
		.btn-group { margin-right: 10px; }
		.col-md-12 {
			position: relative;
			z-index: 2;
			margin: 70px auto 0 auto;
			max-width: 550px;
			background: #efefef;
			border-radius: 10px;
			padding: 20px;
		}
		h2 {
			text-align: center;
			cursor: pointer;
		}
		#resetForm { display: none;	}
		.alert { margin-top: 50px; }
		#credits {
			position: absolute;
			right: 10px;
			bottom: 10px;
			font-size: 10px;
		}
	</style>
	
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="container">

<?php
	
	if (!file_exists("round.txt")){
		if ($_POST['password']==$pass && is_numeric($_POST['reset'])) {
			$file = 'round.txt';
			$content = time()."-5";
			file_put_contents($file,$content, LOCK_EX);
?>

<script>
	window.location = window.location.href;
</script>

<?
		}else {
?>

	<div class="row">
		<div class="col-md-12">
			<form class="form-horizontal" action="" method="POST">
				 <div class="form-group">
					<label for="reset" class="col-sm-3 control-label"><?php echo $NUMBERCHOICES ?></label>
					<div class="col-sm-9">
						<input type="text" name="reset" class="form-control" id="reset" placeholder="1-9">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-sm-3 control-label"><?php echo $PASSWORD ?></label>
					<div class="col-sm-9">
						<input type="password" name="password" class="form-control" id="password" placeholder="<?php echo $PASSWORD ?>">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default"><?php echo $RESET ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
		}
	}else {
		if (isset($_GET['success'])){
?>

				<div class="alert alert-success"><?php echo $SYSTEMRESET ?></div>

<?php
		}else if (isset($_GET['error'])){
?>

				<div class="alert alert-danger"><?php echo $WRONGPASSWORD ?></div>

<?php
		}
?>

	<div class="row">
		<div class="col-sm-5">
			<h4><?php echo $TOTALVOTES ?>:</h4><h3 id="count">0</h3>
			<h4><?php echo $CHOICEVOTES ?>:</h4><table></table>
		</div>
		<div id="graph" class="col-sm-7">
			
		</div>
	</div>

<?php
	if ($_POST['password']==$pass && is_numeric($_POST['reset'])) {
		$file = 'round.txt';
		$content = time()."-".$_POST['reset'];
		file_put_contents($file, $content, LOCK_EX);
?>
	
	<script>
	window.location.replace(window.location+"?success");
	</script>
	
<?php
	}else if ($_POST['password']!="" && $_POST['password']!=$pass){
?>

	<script>
	window.location.replace(window.location+"?error");
	</script>
	
<?php
	}
?>

	<div class="row" id="resetForm">
		<div class="col-md-12">
			<form class="form-horizontal" action="" method="POST">
				 <div class="form-group">
					<label for="reset" class="col-sm-3 control-label"><?php echo $NUMBERCHOICES ?></label>
					<div class="col-sm-9">
						<input type="text" name="reset" class="form-control" id="reset" placeholder="1-9">
					</div>
				</div>
				<div class="form-group">
					<label for="password" class="col-sm-3 control-label"><?php echo $PASSWORD ?></label>
					<div class="col-sm-9">
						<input type="password" name="password" class="form-control" id="password" placeholder="<?php echo $PASSWORD ?>">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-9">
						<button type="submit" class="btn btn-default"><?php echo $RESET ?></button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="buttons">
	<div class="btn-group">
		<button value="start" class="btn btn-default disabled"><span class="glyphicon glyphicon-play"></span> <?php echo $START ?></button>
		<button value="stop" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span> <?php echo $PAUSE ?></button>
	</div>
	
	<div class="btn-group dropup">
		<button type="button" class="btn btn-default dropdown-toggle">
		<?php echo $GRAPH ?> <span class="caret"></span>
		</button>
		<ul class="dropdown-menu">
			<li><a href="#"><?php echo $PIE ?></a></li>
			<li><a href="#"><?php echo $CATEGORY ?></a></li>
		</ul>
	</div>
	
	<button value="reset" class="btn btn-default"><span class="glyphicon glyphicon-repeat"></span> <?php echo $RESET ?></button>
</div>

<!-- Scripts -->
<script src="assets/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.pie.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.categories.min.js"></script>
<script>

var checkLoop;
var choice;
var id;
var nChoices = 5;
var graph = "cat";

setTimeout(function() {
	$(".alert").slideUp();
},2000);

$("button").click(function(){
	if ($(this).val() == "start"){
		setLoop();
		$("button[value=start]").addClass("disabled");
		$("button[value=stop]").removeClass("disabled");
	}else if ($(this).val() == "stop"){
		clearInterval(checkLoop);
		$("button[value=start]").removeClass("disabled");
		$("button[value=stop]").addClass("disabled");
	}else if ($(this).val() == "reset"){
		$("#resetForm").slideToggle();
	}
});

$("li a").click(function() {
	if ( $(this).html() == "<?php echo $PIE ?>" ){
		graph = "pie";
	}else if ( $(this).html() == "<?php echo $CATEGORY ?>" ){
		graph = "cat";
	}
	getData(false);
});

$(".dropdown-toggle").click(function() {
	$(this).parent().children(".dropdown-menu").toggle();
});

$(".dropdown-toggle").focusout(function() {
	$(this).parent().children(".dropdown-menu").hide();
});

setLoop();
function setLoop() {
	if (typeof(checkLoop) != "undefined"){
		clearInterval(checkLoop);
	}
	checkLoop = setInterval(function() {
		getData(false);
	},750);
}

getData(true);
function getData(firstTime) {
	$.ajax({
		url: "round.txt",
		success:function(result){
			if (result !=""){
				if (firstTime) {
					id = result.substr(0,10);
					result = result.substr(11);
					nChoices = result.charAt(0);
					result = result.substr(2);
				
					for (var i=0; i<nChoices; i++){
						switch(i+1){
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
						$(".container table").append("<tr><td><span class='label label-"+color+"'>"+choice+"</span></td><td class='count'></td><td class='percent'></td></tr>");
					}
				}else {
					result = result.substr(13);
				}
			
				result = result.split("-");
				
				var count = result.length;
				$("#count").html(count);
				var partResult = [];
				for ( var i=0; i<nChoices;i++ ){
					partResult[i] = 0;
				}
				for ( var i=0; i<count;i++ ){
					if (partResult[result[i]]) {
						partResult[result[i]]++;
					}else {
						partResult[result[i]] = 1;
					}
				}
				
				var data = [];
				partResult.forEach(function(v,n){
					$('.count:eq('+(n-1)+')').html(v);
					if (v==0) {
						$('.percent:eq('+(n-1)+')').html("0%");
					}else {
						$('.percent:eq('+(n-1)+')').html(Math.round(v/count*100)+"%");
					}
					if (graph == "pie"){
						data.push({ data: v, label: n, color: assignColor(n) });
					} else if (graph == "cat") {
						data.push({ data: [[n,v]], label: n, color: assignColor(n) });
					}
				});
				data.shift();
				
				if (graph == "pie"){
					$.plot('#graph', data, {
						series: {
							pie: {
								show: true,
								innerRadius: 0.5,
								stroke: { width: 5 },
								label: { show: false }
							}
						},
						legend: { show: false }
					});
				} else if (graph == "cat") {
					$.plot("#graph", data, {
						series: {
							bars: { 
								show: true,
								barWidth: 1,
								fill: 0.8
							}
						},
						legend: { show: false },
						xaxis: { show: false },
						yaxis: {
							min: 0,
							minTickSize: 1,
							tickDecimals: 0
						},
						grid: { show: true }
					});
				}
			}
			
		},
		error:function(error){
			$("html").append("<p><?php echo $TRYAGAIN ?></p>");
		},
	});
}

function assignColor(n) {
	switch(n){
		case 1:
			return "#FF0A0A";
			break;
		case 2:
			return "#235BF3";
			break;
		case 3:
			return "#FFDB0A";
			break;
		case 4:
			return "#1FB01F";
			break;
		case 5:
			return "#222222";
			break;
		case 6:
			return "#F70A9F";
			break;
		case 7:
			return "#FF9116";
			break;
		case 8:
			return "#0ED5F1";
			break;
		default:
			return "#aaaaaa";
			break;
	}
}

</script>

<?php
	}
?>
<div id="credits"><?php echo $CREDITS ?> <a href="http://www.tuurlievens.net/" target="_blank">Tuur Lievens</a>.</div>
</body>
</html>