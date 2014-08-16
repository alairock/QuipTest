<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta http-equiv="x-ua-compatible" content="ie=edge, chrome=1" />
		<title>untitled</title>

	</head>
	<body>
	<?php
	require 'vendor/autoload.php';
	Predis\Autoloader::register();
	$redis = new \Predis\Client('tcp://localhost:6379');
	$redis->incr('BLINK');
	?>
		<script src=""></script>
	</body>
</html>





<?php















exit;
$prefix = "Pets";
$pets = ['dog', 'cat', 'bird'];
$quip = new Quip\Test($prefix, $pets);

$response = $quip->redis->
pr($response);
if (!empty($_POST['winner'])) {
//	$quip->setSuccess($_POST['winner']);
}
?>

<form name="quiptest" action="/" method="post">
	Current Winner: <input type="text" name="winner" value="<?php echo $quip->getTestVar(); ?>">
	<input type="submit" value="Vote">
</form>
<br><br><br><br>

<?php echo $quip->getTestVar(); ?>
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