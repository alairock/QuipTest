<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge, chrome=1" />
		<title>untitled</title>
		<link rel="icon"
			  type="image/png"
			  href="http://example.com/myicon.png">
	</head>
	<body>

	<?php
	require 'vendor/autoload.php';
	$prefix = "Friend";
	$pets = ['dog', 'cat', 'bird'];
	$quip = new Quip\Test($prefix, $pets);

	if (!empty($_POST['winner'])) {
		$quip->setSuccess($_POST['winner']);
	}
	?>

	<form name="quiptest" action="/" method="post">
		Current Winner: <input type="text" name="winner" value="<?php echo $quip->getTestVar(); ?>">
		<input type="submit" value="Vote">
	</form>
	<br><br><br><br>

	<?php
		pr($quip->getStats());
	?>

	<?php
	function pr($val) {
		echo "<pre>";
		print_r($val);
		echo "</pre>";
	}
	?>
	</body>
</html>
