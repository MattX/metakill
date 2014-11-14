<?php
error_reporting(E_ALL);
ini_set( 'display_errors', 1 );

session_start();
if(isset($_GET['disconnect'])) {
	session_destroy();
}

include 'misc.php';
$db = connect();
$skip = false;
?>

<!DOCTYPE html>
<html>
<head>
<title>Killer du ski</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div id="container">

<?php

if(!isset($_SESSION['id'])) {
	include 'login.php';
}

if(!$skip) {
	act($db, $_GET, $_POST, $_SESSION['id']);
	$u = get_user($db, $_SESSION['id']);
?>
<p><span class="uname"><?php echo($u['name']); ?></span> <a href="index.php?page=change_user">Mon profil</a></p>
<?php
	if(!isset($_GET['page'])) {
		include 'list.php';
	} else {
	switch($_GET['page']) {
	case 'change_pw':
		include 'chpw.php';
		break;
	case 'change_user':
		include 'chuser.php';
		break;
	case 'add_user':
		include 'adduser.php';
		break;
	case 'add_killer':
		include 'addkiller.php';
		break;
	case 'killer_admin':
		include 'chkiller.php';
		break;
	case 'killer_details':
		include 'killer.php';
		break;
	case 'main': default:
		include 'list.php';
		break;
	}
	}
}
?>

</div>
</body>
</html>
