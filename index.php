<?php require 'vendor/autoload.php';
$testName = "button";
$tags = ['orange', 'green', 'white']; // test tags
$buttonTest = new \Quip\Test('tcp://192.168.99.100:6379', $testName, $tags);
$tag = $buttonTest->getTag();
if (!empty($_GET['tag_name'])) {
	$buttonTest->markSuccess($_GET['tag_name']);
}
$stats = $buttonTest->getStats();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="x-ua-compatible" content="ie=edge, chrome=1"/>
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<title>QuipTest</title>
	<link rel="icon"
		  type="image/png"
		  href="http://example.com/myicon.png">
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<link href="//maxcdn.bootstrapcdn.com/bootswatch/3.2.0/united/bootstrap.min.css" rel="stylesheet">
	<style>
		* {
			box-sizing: border-box;
		}
		.row {
			margin-top: 100px;
			text-align: center;
		}

		.stats-container > .stat-container {
			float: left;
			margin-right: 10px;
		}

		.stats-container {
			width: 330px;
			margin: auto;
		}
		.stats-container:before,
		.stats-container:after {
			content: " "; /* 1 */
			display: table; /* 2 */
		}

		.stats-container:after {
			clear: both;
		}

		.color, .stat-container {
			width: 100px;
			height: 60px;
			margin: auto;
			border: 1px solid #868686;
			color: #868686;
			font-size: 16px;
			font-weight: bold;;
			box-sizing: border-box;
			padding-top: 15px;
			margin-bottom: 20px;
		}

		.orange {
			background-color: orange;
		}

		.green {
			background-color: green;
		}

		.white {
			background-color: white;
		}
	</style>
</head>
<body>
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<h1 class="cover-heading">QuipTest</h1>

		<p class="lead"> Do you like the color of this button? </p>

		<p class="lead">

		<div class="color  <?= $tag; ?> clear"> <?= $tag; ?></div>
		<a href="?tag_name=<?= $tag; ?>" class="btn btn-success btn-default">Yes</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a onclick="javascript:location.href = window.location.href.replace(window.location.search,'');"
		   class="btn btn-default btn-primary reload">No</a>
		</p>
		<p>&nbsp;</p>
		<p><h1>Stats</h1></p>
		<p>
		<div class="stats-container">
			<div class="stat-container orange"><?= ceil($stats['cases']['orange']['success_rate']); ?>%</div>
			<div class="stat-container white"><?= ceil($stats['cases']['white']['success_rate']); ?>%</div>
			<div class="stat-container green"><?= ceil($stats['cases']['green']['success_rate']); ?>%</div>
		</div>
		</p>
		<p>&nbsp;</p>
		<p><h2>Total</h2></p>
		<p>
			<h1><?= $stats['tests_performed']; ?></h1>
		</p>
	</div>
</div>

<script src="//code.jquery.com/jquery-2.1.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
