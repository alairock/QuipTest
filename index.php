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
	$testName = "button";
	$tags = ['orange', 'green', 'white']; // test tags
	$buttonTest = new \Quip\Test($testName, $tags);

	if (!empty($_POST['tag_name'])) {
		$buttonTest->markSuccess($_POST['tag_name']);
	}
	?>

	<form name="quiptest" action="/" method="post">
		Current Winner: <input type="text" name="tag_name" value="<?php echo $buttonTest->getTag(); ?>">
		<input type="submit" value="Vote">
	</form>
	</body>
</html>
