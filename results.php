<?php

ob_start();
session_start();
include_once("options.php");
include_once("assets/language.php");

if (is_dir($folder) && file_exists($folder."/round.txt")){
	$loggedin = false;
	if (isset($_SESSION["loggedin"])&&$_SESSION["loggedin"]) $loggedin = true;
	if ($loggedin) {
		$nChoices = explode($separator, file_get_contents($folder."/round.txt",null,null,11) );
		$activeQuestion = file_get_contents($folder."/active.txt");
	}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $RESULTS ?></title>
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
		html, body { height: 100%; }
		body { background-color: #fefefe; }
		.container {
			padding-top: 50px;
			min-height: 100%;
		}
		.row{
			overflow: auto;
			padding-bottom: 75px;
		}
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
		h2 {
			text-align: center;
			cursor: pointer;
		}
		#footer {
			position: relative;
			height: 45px;
			margin: 0 10px;
			margin-top: -45px;
			clear: both;
		}
		#buttons { float: left; }
		#buttons .btn-group { margin: 0 }
		.seperator { margin: 0 10px 0 0; }
		#buttons form {
			display: inline-block;
			vertical-align: middle;
			max-width: 200px;
			margin-left: 10px;
		}
		#credits {
			float: right;
			font-size: 10px;
			padding-top: 21px;
		}
		#popup {
			display: none;
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			z-index: 2;
			text-align: center;
			background: rgba(0,0,0,0.7);
			cursor: pointer;
		}
		#popup > * {
			position: absolute;
			top: 50%;
			left: 50%;
			width: 768px;
		}
		#popup iframe {
			height: 400px;
			margin-top: -200px;
			margin-left: -384px;
			box-sizing: border-box;
			border-radius: 15px;
			border: 2px solid #000;
			box-shadow: 0 0 15px #000;
		}
		#popup div {
			text-align: right;
			margin-left: -399px;
			margin-top: 205px;
			color: #eee;
			text-decoration: underline;
		}
		@media (max-width: 768px){
			.container { padding-top: 0px; }
			.btnLabel { display: none; }
			#popup iframe {
				width: 100%;
				max-width: 768px;
				position: static;
				margin: 50px 0 0 0;
				border-radius: 0;
				border: none;
			}
			#popup div {
				position: static;
				padding: 10px 20px 0 0;
				width: 100%;
				margin: 0;
			}
		}
	</style>
	
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>

<div id="popup">
	<iframe src="admin.php?iframe"></iframe>
	<div><?php echo $CLOSE ?></div>
</div>

<div class="container">
	<div class="row">
		<div class="col-sm-5">
			<h3 id="questionCount"></h3>
			<h4><?php echo $TOTALVOTES ?>:</h4><h3 id="count">0</h3>
			<h4><?php echo $CHOICEVOTES ?>:</h4><table></table>
		</div>
		<div id="graph" class="col-sm-7">
			
		</div>
	</div>
	
</div>

<div id="footer">
	<div id="buttons">
		
		<?php if ($livetoggle) { ?>
		
		<div class="btn-group">
			<button value="start" class="status btn btn-default"><span class="glyphicon glyphicon-play"></span> <span class="btnLabel"><?php echo $START ?></span></button>
			<button value="stop" class="status btn btn-default"><span class="glyphicon glyphicon-pause"></span> <span class="btnLabel"><?php echo $PAUSE ?></span></button>
		</div>
		
		<?php } ?>
		
		<div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle">
			<span class="glyphicon glyphicon-stats"></span> <span class="btnLabel"><?php echo $GRAPH ?></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
				<li><a href="#"><?php echo $CATEGORY ?></a></li>
				<li><a href="#"><?php echo $PIE ?></a></li>
			</ul>
		</div>
		
		<div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle">
			<span class="glyphicon glyphicon-question-sign"></span> <span class="btnLabel"><?php echo $QUESTION ?></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" id="questionsDrop">
				
			</ul>
		</div>
		
		<?php if ($loggedin) { ?>
		
		<div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle">
			<span class="glyphicon glyphicon-floppy-open"></span> <span class="btnLabel"><?php echo $ROUND ?></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" id="folderDrop">
				<?php
					$time = file_get_contents($folder."/round.txt",null,null,null,10);	
					echo "<li><a href='#'><u>".$folder."</u><i> (".date("j/n/Y",$time).")</i></a></li>";
					if ($handle = opendir('.')) {
						$blacklist = array('assets',$folder, '.git');
						$dirs = array_filter(glob('*'), 'is_dir');
						
						foreach ($dirs as $dir) {
							if (!in_array($dir, $blacklist)) {
								$t = file_get_contents($dir."/round.txt",null,null,null,10);
								echo "<li><a href='#'>".$dir."<i> (".date("j/n/Y",$t).")</i></a></li>";
							}
						}
						closedir($handle);
					}
				?>
			</ul>
		</div>
		<span class="seperator"></span>
		<?php if (count($nChoices) > $activeQuestion) { ?>
		<div class="btn-group">
			<button id="next" class="btn btn-default"><span class="glyphicon glyphicon-plus"></span> <span class="btnLabel"><?php echo $NEXT ?></span></button>
		</div>
		<?php } ?>
		
		<div class="btn-group">
			<button id="admin" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> <span class="btnLabel">Admin</span></button>
		</div>
		
		<?php } ?>
		
		<form action="assets/login.php" method="post">
			<?php if (!$loggedin) { ?>
			
			<div class="input-group">
				<input type="password" placeholder="<?php echo strtolower($PASSWORD) ?>" name="pass" class="form-control" />
				<span class="input-group-btn">
					<button class="btn btn-default"><span class="glyphicon glyphicon-log-in"></span></button>
				</span>
			</div>
			
			<?php } else { ?>
			
			<button name="logout" class="btn btn-default"><span class="glyphicon glyphicon-log-out"></span> <span class="btnLabel"><?php echo $LOGOUT ?></span></button>
			
			<?php } ?>
		</form>
	</div>
	<div id="credits"><?php echo $CREDITS ?> <a href="http://www.tuurlievens.net/" target="_blank">Tuur Lievens</a>.</div>
</div>

<!-- Scripts -->
<script src="assets/jquery-1.10.2.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.pie.min.js"></script>
<script language="javascript" type="text/javascript" src="assets/flot/jquery.flot.categories.min.js"></script>
<script>

var checkLoop;
var graph = "cat";
var nChoices;
var question = 1;
var folder = "<?php if (isset($_GET['round'])) echo $_GET['round']; else echo $folder; ?>";

if (window.location.hash && !isNaN(window.location.hash.substring(1)) && window.location.hash.substring(1) != ""){
	question = window.location.hash.substring(1);
}	
$("#questionCount").html("<?php echo $QUESTION ?>"+" "+question);
Init();
	
function notificaton(type,text) {
	$('<div class="alert alert-'+type+'">'+text+'</div>').prependTo(".container").delay(1000).slideUp(function(){$(this).remove()});
}

function changeQuestion(t) {
	question = t.attr('id').substring(1);
	window.location.hash = question;
	$("#questionCount").html("<?php echo $QUESTION ?> "+question);
	getData();
}
	
function Init(){
	
	$.ajax({
		url: folder+"/round.txt",
		success:function(result){
			var id = result.substr(0,10);
			result = result.substr(11).split("<?php echo $separator ?>");
			nChoices = result;
			$("#questionsDrop").html("");
			if (nChoices.length > 1){
				for (var i=0; i<nChoices.length; i++){
					$("#questionsDrop").append("<li><a href='#' id='q"+(i+1)+"'><?php echo $QUESTION ?> "+(i+1));
				}
				$("#questionsDrop").parents(".btn-group").show();
				$("#questionCount").show();
			}else {
				$("#questionsDrop").parents(".btn-group").hide();
				$("#questionCount").hide();
			}
			
			getData();
			setLoop();
			$("li a").on("mousedown",function() {
				if ( $(this).parents('.dropdown-menu').attr('id') == "questionsDrop" ){
					changeQuestion($(this));
					question = $(this).attr('id').substring(1);
					window.location.hash = question;
					$("#questionCount").html("<?php echo $QUESTION ?> "+question);
					getData();
					
				}
			});
		}
	});
	
}

function getData() {
	$.ajax({
		url: folder+"/"+question+".txt",
		success:function(result){
			result = result.split("<?php echo $separator ?>");
			result.shift();
			
			var count = result.length;
			$("#count").html(count);
			$(".container table").html("");
			var partResult = [];
			for ( var i=0; i<nChoices[question-1];i++ ){
				partResult[i] = 0;
				$(".container table").append("<tr><td><span class='label label-"+assignColor(i+1)[0]+"'>"+assignColor(i+1)[2]+"</span></td><td class='count'></td><td class='percent'></td></tr>");
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
					data.push({ data: v, label: n, color: assignColor(n)[1] });
				} else if (graph == "cat") {
					data.push({ data: [[n,v]], label: n, color: assignColor(n)[1] });
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
							barWidth: 0.8,
							fill: 1
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
			
		},
		error:function(error){
			notificaton("danger","<?php echo $NOTFOUND ?>")
		}
	});
}
	
function setLoop() {
	if (typeof(checkLoop) != "undefined"){
		clearInterval(checkLoop);
	}
	checkLoop = setInterval(function() {
		getData();
	},<?php echo $refreshinterval; ?>);
}

function assignColor(n) {
	switch(n){
		case 1:
			return ["red","#FF0A0A","<?php echo $RED ?>"];
			break;
		case 2:
			return ["blue","#235BF3","<?php echo $BLUE ?>"];
			break;
		case 3:
			return ["yellow","#FFDB0A","<?php echo $YELLOW ?>"];
			break;
		case 4:
			return ["green","#1FB01F","<?php echo $GREEN ?>"];
			break;
		case 5:
			return ["black","#222222","<?php echo $BLACK ?>"];
			break;
		case 6:
			return ["pink","#F70A9F","<?php echo $PINK ?>"];
			break;
		case 7:
			return ["orange","#FF9116","<?php echo $ORANGE ?>"];
			break;
		case 8:
			return ["cyan","#0ED5F1","<?php echo $CYAN ?>"];
			break;
		default:
			return ["white","#aaaaaa","<?php echo $WHITE ?>"];
			break;
	}
}

$("li a").on("mousedown",function() {
	if ( $(this).html() == "<?php echo $PIE ?>" ){
		graph = "pie";
		getData();
	}else if ( $(this).html() == "<?php echo $CATEGORY ?>" ){
		graph = "cat";
		getData();
	}else if ( $(this).parents('.dropdown-menu').attr('id') == "folderDrop" ){
		folder = $(this).clone().children('i').remove().end().text();
		window.location.hash = "";
		question = 1;
		$(".row h3:not(#count)").html("<?php echo $QUESTION ?> "+question);
		clearInterval(checkLoop);
		Init();
	}
});
	
$("#popup,#admin").click(function(){
	$("#popup").fadeToggle();
});
	
$("#next").click(function(){
	$(this).attr("disabled");
	$.ajax({
		type: "POST",
		url: "admin.php",
		data: "incr=true",
		success: function(data){
			notificaton("info","<?php echo $ACTIVEQUESTION ?>: "+data);
			$("#next").removeAttr("disabled");
			if (data>=nChoices.length) {
				$("#next").fadeOut();
			}
			changeQuestion( $("#questionsDrop li:nth-child("+data+") a") );
		}
	});
});

$("#buttons button").click(function(){
	if ($(this).val() == "start"){
		setLoop();
		$("#buttons .status").toggle();
	}else if ($(this).val() == "stop"){
		clearInterval(checkLoop);
		$("#buttons .status").toggle();
	}
});

$(".dropdown-toggle").click(function() {
	$(this).parent().children(".dropdown-menu").toggle();
});

$(".dropdown-toggle").focusout(function() {
	$(this).parent().children(".dropdown-menu").hide();
});

</script>
</body>
</html>
	
<?php
		
	}else {
		echo "<html><body><p>".$NOTACTIVE."</p>";
	}
	ob_flush();

?>