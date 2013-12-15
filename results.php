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
		.btn-group { margin-right: 10px; }
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
		#credits {
			float: right;
			font-size: 10px;
			padding-top: 21px;
		}
		@media (max-width: 768px){
			.container { padding-top: 0px; }
		}
	</style>
	
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
<div class="container">

<?php
	
	if (is_dir($folder) && file_exists($folder."/round.txt")){
	
?>

	<div class="row">
		<div class="col-sm-5">
			<h3></h3>
			<h4><?php echo $TOTALVOTES ?>:</h4><h3 id="count">0</h3>
			<h4><?php echo $CHOICEVOTES ?>:</h4><table></table>
		</div>
		<div id="graph" class="col-sm-7">
			
		</div>
	</div>
	
</div>


<div id="footer">
	<div id="buttons">
		<div class="btn-group">
			<button value="start" class="btn btn-default disabled"><span class="glyphicon glyphicon-play"></span> <span class="btnLabel"><?php echo $START ?></span></button>
			<button value="stop" id="stop" class="btn btn-default"><span class="glyphicon glyphicon-pause"></span> <span class="btnLabel"><?php echo $PAUSE ?></span></button>
		</div>
		
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
		
		<div class="btn-group dropup">
			<button type="button" class="btn btn-default dropdown-toggle">
			<span class="glyphicon glyphicon-floppy-open"></span> <span class="btnLabel"><?php echo $ROUND ?></span> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" id="folderDrop">
				<?php
					
					if ($handle = opendir('.')) {
						$blacklist = array('.', '..', 'options.php', 'reset.php', 'results.php', 'vote.php', '.htaccess', 'assets', '.gitattributes', '.gitignore', 'README.md', '.git');
						while (false !== ($file = readdir($handle))) {
							if (!in_array($file, $blacklist)) {
								echo "<li><a href='#'>".$file."</a></li>";
							}
						}
						closedir($handle);
					}
					
				?>
			</ul>
		</div>
		
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

if (window.location.hash && !isNaN(window.location.hash.substring(1))){
	var question = window.location.hash.substring(1);
}
$(window).resize(function(){ hideLabels(); });

	hideLabels();
function hideLabels(){
	if ( $(window).width() < 520 ){
		$("#buttons .btnLabel").hide();
	}else {
		$("#buttons .btnLabel").show();
	}
}
	
$(".row h3").html("<?php echo $QUESTION ?> "+question);

Init();
function Init(){
	
	$.ajax({
		url: folder+"/round.txt",
		success:function(result){
			var id = result.substr(0,10);
			result = result.substr(11).split("-");
			nChoices = result;
			$("#questionsDrop").html("");
			if (nChoices.length > 1){
				for (var i=0; i<nChoices.length; i++){
					$("#questionsDrop").append("<li><a href='#' id='q"+(i+1)+"'><?php echo $QUESTION ?> "+(i+1));
				}
			}else {
				$("#questionsDrop").parents(".btn-group").remove();
			}
			
			getData();
			setLoop();
			$("li a").on("mousedown",function() {
				if ( $(this).parents('.dropdown-menu').attr('id') == "questionsDrop" ){
					question = $(this).attr('id').substring(1);
					window.location.hash = question;
					$(".row h3").html("<?php echo $QUESTION ?> "+question);
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
			result = result.split("-");
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
			
		},
		error:function(error){
			$("html").append("<p><?php echo $TRYAGAIN ?></p>");
		},
	});
}
	
function setLoop() {
	if (typeof(checkLoop) != "undefined"){
		clearInterval(checkLoop);
	}
	checkLoop = setInterval(function() {
		getData();
	},750);
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
		folder = $(this).html();
		window.location.hash = "";
		question = 1;
		$(".row h3").html("<?php echo $QUESTION ?> "+question);
		clearInterval(checkLoop);
		Init();
	}
});

$("#buttons button").click(function(){
	if ($(this).val() == "start"){
		setLoop();
		$("button[value=start]").addClass("disabled");
		$("button[value=stop]").removeClass("disabled");
	}else if ($(this).val() == "stop"){
		clearInterval(checkLoop);
		$("button[value=start]").removeClass("disabled");
		$("button[value=stop]").addClass("disabled");
	}
});

$(".dropdown-toggle").click(function() {
	$(this).parent().children(".dropdown-menu").toggle();
});

$(".dropdown-toggle").focusout(function() {
	$(this).parent().children(".dropdown-menu").hide();
});

</script>
	
<?php
	}else {
		echo "<html><body><p>".$NOTACTIVE."</p></body></html>";
	}
?>

</body>
</html>